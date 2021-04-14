<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use App\Services\LogService;
use Illuminate\Http\Response;
use Closure;

class Bearer
{
    /** @var AuthService */
    public AuthService $authService;

    /** @var LogService */
    public LogService $logService;

    public function __construct(
        AuthService $authService,
        LogService $logService
    )
    {
        $this->authService = $authService;
        $this->logService = $logService;
    }

    public function handle($request, Closure $next)
    {
        if (!$request->header('authorization')) {
            return response()->json(['message' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        }

        $header = explode('Bearer', $request->header('authorization'));
        $token = trim($header[1]);

        if ($this->authService->isExpired($token)) {
            $this->logService->warning('Unauthorised.', ['Bearer' => $request->header('authorization')]);
            return response()->json(['message' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        } else {
            $this->authService->authorization($token);
        }

        return $next($request);
    }
}
