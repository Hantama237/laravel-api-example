<?php

namespace App\Services;

use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;

class UserService implements IUserService
{
    public function __construct(IUserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }
    public function me(){
        return auth()->user();
    }
}
