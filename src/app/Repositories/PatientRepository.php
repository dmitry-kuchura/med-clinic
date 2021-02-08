<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Models\Patients;

class PatientRepository implements Repository
{
    public function paginate(int $offset)
    {
        return Patients::withCasts(['status'])->orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id)
    {
        // TODO: Implement get() method.
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
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
