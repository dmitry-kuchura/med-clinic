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

    public function findByName(array $data): ?Doctor
    {
        return Doctor::select('*')
            ->where(function ($query) use ($data) {
                if (!isset($data['first_name']) && $data['first_name']) {
                    $query->where('first_name', $data['first_name']);
                }
            })
            ->where(function ($query) use ($data) {
                if (!isset($data['last_name']) && $data['last_name']) {
                    $query->where('last_name', $data['last_name']);
                }
            })
            ->where(function ($query) use ($data) {
                if (!isset($data['middle_name']) && $data['middle_name']) {
                    $query->where('middle_name', $data['middle_name']);
                }
            })
            ->first();
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
