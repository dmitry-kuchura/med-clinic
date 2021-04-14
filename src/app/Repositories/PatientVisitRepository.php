<?php

namespace App\Repositories;

use App\Models\PatientVisit;

class PatientVisitRepository implements Repository
{
    public function getLastVisits(): ?PatientVisit
    {
        return PatientVisit::orderBy('id', 'desc')->first();
    }

    public function get(int $id)
    {
        return PatientVisit::with(['patient', 'patient.user', 'test'])->find($id);
    }

    public function all()
    {
        return PatientVisit::all();
    }

    public function store(array $data): PatientVisit
    {
        return PatientVisit::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientVisit::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        return PatientVisit::where('id', $id)->delete();
    }
}
