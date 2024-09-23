<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTO\UserData;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(UserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password
        ]);
    }
}
