<?php

namespace App\Contracts\Repositories;

use App\DTO\UserData;
use App\Models\User;

interface UserRepositoryInterface
{
    public function create(UserData $data): User;

    public function assignRole(User $user, string $role): User;
}
