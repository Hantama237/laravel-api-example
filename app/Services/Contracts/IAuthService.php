<?php

namespace App\Services\Contracts;

interface IAuthService
{
    public function login(array $credentials);
    public function loginWithPin(array $credentials);
    public function register(array $data);
    public function logout();
    public function forgotPassword(string $email);
    public function resetPassword(array $data);
    public function regeneratePin(string $token, string $email);
}
