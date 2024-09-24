<?php

namespace App\DTO\Auth;

use Illuminate\Http\Request;

class LoginData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    public static function fromRequest(Request $data): self
    {
        return new self(
            $data->get('email'),
            $data->get('password')
        );
    }
}
