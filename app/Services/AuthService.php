<?php

 namespace App\Services;

 use App\Repositories\Contracts\IPlanRepository;
 use App\Repositories\Contracts\IUserRepository;
 use App\Services\Contracts\IAuthService;
 use App\Services\Contracts\ISubscriptionService;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Support\Facades\Mail;
 use Illuminate\Support\Str;
 use Illuminate\Support\Facades\Password;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Validation\ValidationException;
 use App\Mail\SendPin;
 use App\Notifications\ForgotPasswordVerification;

class AuthService implements IAuthService
{
    protected IUserRepository $_userRepository;

    public function __construct(
        IUserRepository $userRepository,
    ) {
        $this->_userRepository = $userRepository;
    }
    public function login(array $credentials)
    {
        $user = $this->_userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credentials do not match.'],
            ]);
        }

        return $user;
    }

    public function loginWithPin(array $credentials)
    {
        $user = $this->_userRepository->findByPin($credentials['pin']);

        if (!$user) {
            throw ValidationException::withMessages([
                'pin' => ['Invalid PIN.'],
            ]);
        }

        return $user;
    }

    public function register(array $data)
    {
        
        $user = null;

        $user = $this->_createB2BUser($data);


        // Create subscription using subscription service

        return $user;
    }

    private function _createB2CUser(array $data)
    {
        do {
            $pin = strtoupper(Str::random(6));
        } while ($this->_userRepository->findByPin($pin));

        $user = $this->_userRepository->create([
            'email' => $data['email'],
            'pin' => $pin,
            'password' => null,
            'email_verified_at' => now(),
        ]);

        Mail::to($user->email)->send(new SendPin($pin));

        // Assign student role to B2C users
        $user->assignRole('student');

        return $user;
    }

    private function _createB2BUser(array $data)
    {
        $user = $this->_userRepository->create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'pin' => null,
        ]);
        
        $user->sendEmailVerificationNotification();

        // Assign partner role to B2B users
        $user->assignRole('partner');

        return $user;
    }

    public function logout()
    {
        // Logout is handled by the controller using Sanctum's token deletion
        // This method can be used for additional cleanup if needed
        return true;
    }

    public function forgotPassword(string $email)
    {
        $user = $this->_userRepository->findByEmail($email);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We cannot find a user with that email address.'],
            ]);
        }

        // Check user role to determine flow
        if ($user->hasRole('student')) {
            // For students, send verification email first
            $token = Str::random(64);
            
            // Store token in pin_reset_tokens table
            DB::table('pin_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => hash('sha256', $token),
                    'created_at' => now(),
                ]
            );

            // Send verification notification
            $user->notify(new ForgotPasswordVerification($token));

            return [
                'message' => 'Verification email has been sent. Please check your email to verify and generate a new PIN.',
                'type' => 'verification'
            ];
        } else {
            // For partners (B2B users), use Laravel's password reset mechanism
            $status = Password::sendResetLink(['email' => $email]);

            if ($status !== Password::RESET_LINK_SENT) {
                throw ValidationException::withMessages([
                    'email' => ['Unable to send password reset link.'],
                ]);
            }

            return [
                'message' => 'Password reset link has been sent to your email address.',
                'type' => 'password_reset'
            ];
        }
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return ['message' => 'Password has been reset successfully.'];
    }

    public function regeneratePin(string $token, string $email)
    {
        // Find the token in pin_reset_tokens table
        $tokenRecord = DB::table('pin_reset_tokens')
            ->where('email', $email)
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$tokenRecord) {
            throw ValidationException::withMessages([
                'token' => ['Invalid or expired verification token.'],
            ]);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            DB::table('pin_reset_tokens')->where('email', $email)->delete();
            throw ValidationException::withMessages([
                'token' => ['Verification token has expired.'],
            ]);
        }

        // Find the user
        $user = $this->_userRepository->findByEmail($email);
        if (!$user || !$user->hasRole('student')) {
            throw ValidationException::withMessages([
                'email' => ['Invalid user or user is not a student.'],
            ]);
        }

        // Generate new unique PIN
        do {
            $pin = strtoupper(Str::random(6));
        } while ($this->_userRepository->findByPin($pin));

        // Update user with new PIN
        $user->update(['pin' => $pin]);

        // Send PIN to email
        Mail::to($user->email)->send(new SendPin($pin));

        // Delete the used token
        DB::table('pin_reset_tokens')->where('email', $email)->delete();

        return [
            'message' => 'Your email has been verified and a new PIN has been sent to your email address.',
            'type' => 'pin_sent'
        ];
    }
}
