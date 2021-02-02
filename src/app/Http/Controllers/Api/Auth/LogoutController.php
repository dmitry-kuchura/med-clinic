<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function logout()
    {
        Auth::logout();

        $this->authService->setExpired(Auth::id());

        return $this->returnResponse([
            'message' => 'Successfully logged out'
        ]);
    }
}
