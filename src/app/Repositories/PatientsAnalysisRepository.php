<?php

namespace App\Repositories;

use App\Models\PatientAnalysis;

class PatientsAnalysisRepository implements Repository
{
    public function get(int $id): PatientAnalysis
    {
        return PatientAnalysis::with(['patient', 'patient.user'])->find($id);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientAnalysis::where('patient_id', $id)->orderBy('id', 'desc')->paginate($offset);
    }

    public function find(int $id)
    {
        return PatientAnalysis::where('patient_id', $id)->with(['patient', 'patient.user'])->get();
    }

    public function all()
    {
        return PatientAnalysis::all();
    }

    public function store(array $data): PatientAnalysis
    {
        return PatientAnalysis::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientAnalysis::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
