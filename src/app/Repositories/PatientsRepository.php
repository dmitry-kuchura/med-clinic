<?php

namespace App\Repositories;

use App\Models\Patient;

class PatientsRepository implements Repository
{
    public function findByExternalId(int $externalId): ?Patient
    {
        return Patient::where('external_id', $externalId)->first();
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
