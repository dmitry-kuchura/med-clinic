<?php

namespace App\Repositories;

use App\Models\Patient;

class PatientsRepository implements Repository
{
    public function findByPhone(string $phone): ?Patient
    {
        return Patient::where('phone', $phone)->first();
    }

    public function findByBirthday(string $birthday): ?Patient
    {
        return Patient::where('birthday', $birthday)->first();
    }

    public function findByName(array $data): ?Patient
    {
        return Patient::select('*')
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

    public function findByEmail(string $email): ?Patient
    {
        return Patient::whereHas('user', function ($query) use ($email) {
            return $query->where('email', $email);
        })->first();
    }

    public function paginate(int $offset)
    {
        return Patient::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?Patient
    {
        return Patient::with('user')->find($id);
    }

    public function all()
    {
        return Patient::all();
    }

    public function store(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(array $data, int $id)
    {
        return Patient::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
