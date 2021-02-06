<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Mail\UpdatePasswordMail;
use App\Services\AuthService;
use Illuminate\Support\Facades\Mail;

class UpdatePasswordController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function update(UpdatePasswordRequest $request)
    {
        $updatedUser = $this->authService->update($request->all());

        if ($updatedUser) {
            Mail::to($updatedUser->email)->send(new UpdatePasswordMail($updatedUser));

            return $this->returnResponse([
                'message' => 'Successfully update password.'
            ]);
        }

        return $this->returnResponse([
            'message' => 'Update password not completed.'
        ], 404);
    }
}
