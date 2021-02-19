<?php

namespace App\Repositories;

use App\Models\User;

class UsersRepository implements Repository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByID(string $id): ?User
    {
        return User::find($id);
    }

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data): User
    {
        return User::create($data);
    }

    public function update(array $data, int $id): void
    {
        User::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
