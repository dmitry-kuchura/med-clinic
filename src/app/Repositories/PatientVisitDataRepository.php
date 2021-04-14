<?php

namespace App\Repositories;

use App\Models\PatientVisitData;

class PatientVisitDataRepository implements Repository
{
    public function get(int $id)
    {
        return PatientVisitData::find($id);
    }

    public function all()
    {
        return PatientVisitData::all();
    }

    public function store(array $data): PatientVisitData
    {
        return PatientVisitData::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientVisitData::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        return PatientVisitData::where('id', $id)->delete();
    }
}
