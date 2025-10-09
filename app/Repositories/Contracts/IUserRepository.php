<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Common\ListRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\SignupRequest;
use App\Http\Requests\User\UpdateContactRequest;
use App\Http\Requests\User\UploadPictureContactRequest;
use App\Http\Requests\User\UserSearchAndPaginationRequest;
use App\Http\Requests\User\UserSearchRequest;
use App\Models\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IUserRepository {
    public function create(array $data);
    public function findByEmail(string $email);
    public function findByPin(string $pin);
}
