<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $registerRequest)
    {
        User::create([
            'email' => $registerRequest->get('email'),
            'name' => $registerRequest->get('name'),
            'password' => bcrypt($registerRequest->get('password')),
        ]);

        $token = Auth::attempt($registerRequest->only(['email', 'password']));

        if (!$token) {
            return $this->returnResponse([
                'success' => false,
                'error' => 'Not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return (new UserResource($registerRequest->user()))->additional([
            'meta' => ['token' => $this->authService->generate(Auth::id())]
        ]);
    }
}
