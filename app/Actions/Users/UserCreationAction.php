<?php

namespace App\Actions\Users;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTO\UserData;
use App\Models\User;

class UserCreationAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function execute(UserData $userData): User
    {
        return $this->userRepository->create($userData);
    }
}
