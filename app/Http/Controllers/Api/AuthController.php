<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IPlanRepository;
use App\Services\Contracts\IUserService;
use App\Services\Contracts\IAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected readonly IUserService $_userService;
    protected readonly IAuthService $_authService;

    public function __construct(
        IUserService $userService, 
        IAuthService $authService,
    ) {
        $this->_userService = $userService;
        $this->_authService = $authService;
    }
    /**
      * Login.
     * @unauthenticated
      * @group Authentication
      *
      * Handle user login and issue an API token.
      */
     public function login(Request $request)
     {
         $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required'],
         ]);

         $user = $this->_authService->login($request->only(['email', 'password']));

         $token = $user->createToken('api-token')->plainTextToken;

         return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'result' => [
                'id'=>$user->id,
                'email'=>$user->email,
                'profile'=>$user->profile,
                'email_verified_at'=>$user->email_verified_at,
                'access_token'=>$token
            ]
        ]);
     }

    /**
     * Login With PIN.
     * @unauthenticated
     * @group Authentication
     *
     * Handle user login with PIN.
     */
    public function loginWithPin(Request $request)
    {
        $request->validate([
            'pin' => ['required'],
        ]);

        $user = $this->_authService->loginWithPin($request->only(['pin']));

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'result' => [
                'id'=>$user->id,
                'email'=>$user->email,
                'profile'=>$user->profile,
                'email_verified_at'=>$user->email_verified_at,
                'access_token'=>$token
            ]
        ]);
    }

    /**
     * Register.
     * @unauthenticated
     * @group Authentication
     *
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'sometimes|required|min:8',
        ]);

        // Get plan to check if password is required for B2B

        $user = $this->_authService->register($request->all());

        return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'result' => new UserResource($user)
        ]);
    }

    /**
     * Me
     * @group Authentication
     * @authenticated
     *
     * Get the currently authenticated user's data.
     */
    public function me(Request $request)
    {
        $user = $this->_userService->me();
        return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'result'=>[
                'id'=>$user->id,
                'email'=>$user->email,
                'profile'=>$user->profile
            ]
        ]);
    }

    /**
     * Logout
     * @group Authentication
     * @authenticated
     *
     * Invalidate the current user's token (logout).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Email Verification
     * @unauthenticated
     * @group Authentication
     *
     * Mark the user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'type'=>'ERROR',
                'code_status'=>404,
                'message' => 'User not found.'
            ], 404);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'type'=>'ERROR',
                'code_status'=>400,
                'message' => 'Invalid verification link.'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'type'=>'SUCCESS',
                'code_status'=>200,
                'message' => 'Email already verified.'
            ], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return response()->json([
            'type'=>'SUCCESS',
            'code_status'=>200,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * Forgot Password
     * @unauthenticated
     * @group Authentication
     *
     * Handle forgot password request. For students, resends PIN to email.
     * For partners, sends password reset link.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $result = $this->_authService->forgotPassword($request->email);

            return response()->json([
                'type' => 'SUCCESS',
                'code_status' => 200,
                'message' => $result['message'],
                'reset_type' => $result['type']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'ERROR',
                'code_status' => 422,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Reset Password
     * @unauthenticated
     * @group Authentication
     *
     * Reset password using token (for partner users only).
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $result = $this->_authService->resetPassword([
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ]);

            return response()->json([
                'type' => 'SUCCESS',
                'code_status' => 200,
                'message' => $result['message']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'ERROR',
                'code_status' => 422,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Regenerate PIN
     * @unauthenticated
     * @group Authentication
     *
     * Regenerate PIN after email verification (for student users only).
     */
    public function regeneratePin(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
        ]);

        try {
            $result = $this->_authService->regeneratePin($request->token, $request->email);

            return response()->json([
                'type' => 'SUCCESS',
                'code_status' => 200,
                'message' => $result['message'],
                'action_type' => $result['type']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'ERROR',
                'code_status' => 422,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }
}