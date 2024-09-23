<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    )
    {
    }

    public static function fromRequest(Request $data): self
    {
        return new self(
            $data->get('name'),
            $data->get('email'),
            Hash::make($data->get('password'))
        );
    }
}
