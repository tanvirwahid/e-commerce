<?php

namespace App\Actions\Auth;

use App\DTO\Auth\LoginData;
use App\Traits\Auth\TokenResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoginAction
{
    use TokenResponseTrait;

    public function execute(LoginData $loginData)
    {
        $credentials = [
            'email' => $loginData->email,
            'password' => $loginData->password
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Incorrect email or password'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }
}
