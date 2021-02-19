<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\PasswordResetsRepository;
use App\Repositories\UsersRepository;
use App\Repositories\UsersTokenRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthService
{
    private UsersTokenRepository $usersTokenRepository;

    private UsersRepository $usersRepository;

    private PasswordResetsRepository $passwordResetsRepository;

    public function __construct(
        UsersTokenRepository $usersTokenRepository,
        UsersRepository $usersRepository,
        PasswordResetsRepository $passwordResetsRepository
    )
    {
        $this->usersTokenRepository = $usersTokenRepository;
        $this->usersRepository = $usersRepository;
        $this->passwordResetsRepository = $passwordResetsRepository;
    }

    public function authorization(string $token): void
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

    public function reset(string $email): ?string
    {
        $user = $this->findUserByEmail($email);

        if ($user) {
            $token = Str::random(40);

            $this->passwordResetsRepository->store([
                'token' => $token,
                'email' => $email,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $token;
        }

        return null;
    }

    public function update(array $data): ?User
    {
        $passwordResets = $this->passwordResetsRepository->find($data['token']);

        if ($passwordResets) {
            $user = $this->findUserByEmail($passwordResets->email);

            $this->usersRepository->update([
                'password' => bcrypt($data['password']),
            ], $user->id);

            $this->passwordResetsRepository->delete($passwordResets->email);

            return $user;
        }

        return null;
    }

    public function isExpired(string $token): bool
    {
        $usersToken = $this->usersTokenRepository->find($token);

        if (!$usersToken) {
            return true;
        }

        return Carbon::parse($usersToken->expired_at)->isPast();
    }

    public function setExpired(int $user_id): void
    {
        $this->usersTokenRepository->expired($user_id);
    }

    public function findUserByToken(string $token): ?User
    {
        $usersToken = $this->usersTokenRepository->find($token);

        return $usersToken->user;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->usersRepository->findByEmail($email);
    }

    public function findTokenByUser(int $user_id): string
    {
        $usersToken = $this->usersTokenRepository->get($user_id);

        return $usersToken->token;
    }
}
