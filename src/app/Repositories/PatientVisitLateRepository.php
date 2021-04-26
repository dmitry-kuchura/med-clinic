<?php

namespace App\Repositories;

use App\Models\PatientVisitLate;

class PatientVisitLateRepository implements Repository
{
    public function get(int $id)
    {
        return PatientVisitLate::find($id);
    }

    public function all()
    {
        return PatientVisitLate::all();
    }

    public function store(array $data): PatientVisitLate
    {
        return PatientVisitLate::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientVisitLate::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        return PatientVisitLate::where('id', $id)->delete();
    }
}
