<?php

namespace App\Repositories;

use App\Models\PatientVisit;
use Illuminate\Database\Eloquent\Collection;

class PatientVisitRepository implements Repository
{
    public function getLastVisits(): ?PatientVisit
    {
        return PatientVisit::orderBy('id', 'desc')->first();
    }

    public function getListForRemind($startTimestamp, $endTimestamp): ?Collection
    {
        return PatientVisit::where('visited_at', '>', $startTimestamp)
            ->where('visited_at', '<', $endTimestamp)
            ->with('patient', 'doctor', 'data')
            ->where('is_marked', false)
            ->limit(50)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getPatientsVisitsList(int $patientId)
    {
        return PatientVisit::where('patient_id', $patientId)
            ->with('data')
            ->limit(25)
            ->orderBy('id', 'desc')
            ->paginate();
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
