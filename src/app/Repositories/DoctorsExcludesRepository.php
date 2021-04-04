<?php

namespace App\Repositories;

use App\Models\DoctorExclude;

class DoctorsExcludesRepository implements Repository
{
    public function find(int $doctorId): ?DoctorExclude
    {
        return DoctorExclude::where('doctor_id', $doctorId)->first();
    }

    public function get(int $id): ?DoctorExclude
    {
        return DoctorExclude::with('user')->find($id);
    }

    public function all()
    {
        return DoctorExclude::all();
    }

    public function store(array $data): DoctorExclude
    {
        return DoctorExclude::create($data);
    }

    public function update(array $data, int $id)
    {
        return DoctorExclude::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
