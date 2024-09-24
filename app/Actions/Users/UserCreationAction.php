<?php

namespace App\Actions\Users;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTO\UserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserCreationAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function execute(UserData $userData, string $role): User
    {
        return DB::transaction(function() use ($userData, $role) {
            return $this->userRepository
                ->assignRole(
                    $this->userRepository->create($userData),
                    $role
                );
        });
    }
}
