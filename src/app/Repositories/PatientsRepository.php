<?php

namespace App\Repositories;

use App\Models\Patients;

class PatientsRepository implements Repository
{
    public function paginate(int $offset)
    {
        return Patients::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?Patients
    {
        return Patients::with('user')->find($id);
    }

    public function all()
    {
        return Patients::all();
    }

    public function store(array $data): Patients
    {
        return Patients::create($data);
    }

    public function update(array $data, int $id)
    {
        return Patients::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
