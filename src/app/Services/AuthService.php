<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UsersTokenRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthService
{
    private UsersTokenRepository $usersTokenRepository;

    public function __construct(UsersTokenRepository $usersTokenRepository)
    {
        $this->usersTokenRepository = $usersTokenRepository;
    }

    public function authorization(string $token)
    {
        Auth::login($this->findUserByToken($token));
    }

    public function generate(int $user_id): string
    {
        $token = Str::random(235);

        $this->setExpired($user_id);

        $this->usersTokenRepository->store([
            'user_id' => $user_id,
            'token' => $token
        ]);

        return $token;
    }

    public function isExpired(string $token): bool
    {
        $usersToken = $this->usersTokenRepository->find($token);

        return Carbon::parse($usersToken->expired_at)->isPast();
    }

    public function setExpired(int $user_id): void
    {
        $this->usersTokenRepository->expired($user_id);
    }

    public function findUserByToken(string $token): User
    {
        $usersToken = $this->usersTokenRepository->find($token);

        return $usersToken->user;
    }

    public function findTokenByUser(int $user_id): string
    {
        $usersToken = $this->usersTokenRepository->get($user_id);

        return $usersToken->token;
    }
}
