<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function profile()
    {
        return $this->returnResponse([
            'user' => new UserResource(Auth::user()),
            'access_token' => $this->authService->findTokenByUser(Auth::id()),
            'token_type' => 'bearer',
        ]);
    }
}
