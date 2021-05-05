<?php

namespace App\Repositories;

use App\Models\PatientVisitLate;

class PatientVisitLateRepository implements Repository
{
    public function getLastVisits()
    {
        return PatientVisitLate::select('external_id')
            ->where('is_marked', false)
            ->orderBy('id', 'asc')
            ->limit(25)
            ->get();
    }

    public function markedLateVisit(int $externalId)
    {
        PatientVisitLate::where('external_id', $externalId)->update(['is_marked' => true]);
    }

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
