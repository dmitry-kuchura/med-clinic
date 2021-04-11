<?php

namespace App\Repositories;

use App\Models\DoctorApproved;

class DoctorsExcludesRepository implements Repository
{
    public function find(int $doctorId): ?DoctorApproved
    {
        return DoctorApproved::where('doctor_id', $doctorId)->first();
    }

    public function get(int $id): ?DoctorApproved
    {
        return DoctorApproved::with('user')->find($id);
    }

    public function all()
    {
        return DoctorApproved::all();
    }

    public function store(array $data): DoctorApproved
    {
        return DoctorApproved::create($data);
    }

    public function update(array $data, int $id)
    {
        return DoctorApproved::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
