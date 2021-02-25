<?php

namespace App\Repositories;

use App\Models\PatientsTests;

class PatientsTestsRepository implements Repository
{
    public function get(int $id)
    {
        return PatientsTests::with(['patient', 'patient.user', 'test'])->find($id);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientsTests::where('patient_id', $id)->with(['patient', 'patient.user', 'test'])->orderBy('id', 'desc')->paginate($offset);
    }

    public function find(int $id)
    {
        return PatientsTests::where('patient_id', $id)->with(['patient', 'patient.user', 'test'])->get();
    }

    public function all()
    {
        return PatientsTests::all();
    }

    public function store(array $data): PatientsTests
    {
        return PatientsTests::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientsTests::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
