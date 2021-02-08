<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $user = Auth::attempt($request->all(), $request->get('remember'));

        if (!$user) {
            return $this->returnResponse([
                'error' => 'Sorry we couldn\'t sign you in with those details.'
            ], 422);
        }

        return $this->returnResponse([
            'user' => new UserResource($request->user()),
            'access_token' => $this->authService->generate(Auth::id()),
            'token_type' => 'bearer',
            'is_admin' => $request->user()->isAdmin(),
        ]);
    }
}
