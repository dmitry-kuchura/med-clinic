<?php

namespace App\Repositories;

use App\Models\PatientTest;

class PatientsTestsRepository implements Repository
{
    public function get(int $id)
    {
        return PatientTest::with(['patient', 'patient.user', 'test'])->find($id);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientTest::where('patient_id', $id)->with(['patient', 'patient.user', 'test'])->orderBy('id', 'desc')->paginate($offset);
    }

    public function find(int $id)
    {
        return PatientTest::where('patient_id', $id)->with(['patient', 'patient.user', 'test'])->get();
    }

    public function all()
    {
        return PatientTest::all();
    }

    public function store(array $data): PatientTest
    {
        return PatientTest::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientTest::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
