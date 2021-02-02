<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Services\AuthService;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function reset(ResetPasswordRequest $request)
    {
        $email = $request->get('email');

        $token = $this->authService->reset($email);

        if ($token) {
            Mail::to($email)->send(new ResetPasswordMail($token));

            return $this->returnResponse([
                'message' => 'Successfully sent email.'
            ]);
        }

        return $this->returnResponse([
            'message' => 'user with this email not found.'
        ], 404);
    }
}
