<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorsRepository implements Repository
{
    public function findByPhone(string $phone): ?Doctor
    {
        return Doctor::where('phone', $phone)->first();
    }

    public function findByEmail(string $email): ?Doctor
    {
        return Doctor::whereHas('user', function ($query) use ($email) {
            return $query->where('email', $email);
        })->first();
    }

    public function findByBirthday(string $birthday): ?Doctor
    {
        return Doctor::where('birthday', $birthday)->first();
    }

    public function paginate(int $offset)
    {
        return Doctor::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?Doctor
    {
        return Doctor::with('user')->find($id);
    }

    public function all()
    {
        return Doctor::all();
    }

    public function store(array $data): Doctor
    {
        return Doctor::create($data);
    }

    public function update(array $data, int $id)
    {
        return Doctor::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
