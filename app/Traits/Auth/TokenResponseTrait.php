<?php

namespace App\Traits\Auth;

trait TokenResponseTrait
{
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl'),
        ]);
    }
}
