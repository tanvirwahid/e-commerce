<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Users\UserCreationAction;
use App\DTO\Auth\LoginData;
use App\DTO\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Traits\Auth\TokenResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use TokenResponseTrait;

    public function __construct(
        private LoginAction $loginAction
    ) {}

    public function register(
        RegistrationRequest $request,
        UserCreationAction $userCreationAction
    ) {

        $user = $userCreationAction->execute(
            UserData::fromRequest($request),
            $request->get('is_admin') ? 'admin' : 'user'
        );

        return response()->json($user, JsonResponse::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        return $this->loginAction->execute(
            LoginData::fromRequest($request)
        );
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
