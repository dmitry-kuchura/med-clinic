<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Response;

class Bearer
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {
        if (!$request->header('authorization')) {
            return response()->json(['message' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        }

        $header = explode('Bearer', $request->header('authorization'));
        $token = trim($header[1]);

        if ($this->authService->isExpired($token)) {
            return response()->json(['message' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        } else {
            $this->authService->authorization($token);
        }

        return $next($request);
    }
}
