<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Actions\Users\UserCreationAction;
use App\DTO\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(
        RegistrationRequest $request,
        UserCreationAction  $userCreationAction
    )
    {
        try {
            $user = $userCreationAction->execute(
                UserData::fromRequest($request)
            );

            return response()->json([
                'user' => $user,
                'message' => 'Registration Successfull'
            ], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
