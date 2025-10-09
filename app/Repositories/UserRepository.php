<?php

 namespace App\Repositories;

 use App\Http\Requests\Common\ListRequest;
 use App\Http\Requests\User\ChangePasswordRequest;
 use App\Http\Requests\User\SignupRequest;
 use App\Http\Requests\User\UpdateContactRequest;
 use App\Http\Requests\User\UploadPictureContactRequest;
 use App\Http\Requests\User\UserSearchAndPaginationRequest;
 use App\Http\Requests\User\UserSearchRequest;
 use App\Models\BaseModel;
 use App\Models\Contact;
 use App\Models\Permission;
 use App\Models\Policy;
 use App\Models\Role;
 use App\Models\User;
 use App\Repositories\Contracts\IUserRepository;
 use Illuminate\Contracts\Pagination\LengthAwarePaginator;
 use Illuminate\Support\Collection;
 use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByPin(string $pin): ?User
    {
        return User::where('pin', $pin)->first();
    }
}
