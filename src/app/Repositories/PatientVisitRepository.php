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

    public function getListForRemind(string $timestamp): ?Collection
    {
        return PatientVisit::where('appointment_at', '>', $timestamp)
            ->where('is_mark', false)
            ->limit(50)
            ->orderBy('appointment_at', 'asc')
            ->groupBy('id', 'patient_id', 'doctor_id', 'appointment_at')
            ->get();
    }

    public function getPatientsVisitsList(string $timestamp): ?Collection
    {
        return PatientVisit::where('appointment_at', '>', $timestamp)
            ->with('patient', 'doctor', 'data')
            ->limit(25)
            ->orderBy('appointment_at', 'asc')
            ->get();
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
