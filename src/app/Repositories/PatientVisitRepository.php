<?php

namespace App\Repositories;

use App\Models\PatientVisit;

class PatientVisitRepository implements Repository
{
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
        $model = new PatientVisit();

        $model->patient_name = $data['patient_name'] ?? null;
        $model->doctor_name = $data['doctor_name'] ?? null;
        $model->external_id = $data['external_id'];
        $model->visited_at = $data['visited_at'];
        $model->patient_id = $data['patient_id'];
        $model->doctor_id = $data['doctor_id'];

        $model->save();

        return $model;
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
