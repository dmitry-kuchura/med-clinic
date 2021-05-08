<?php

namespace App\Repositories;

use App\Models\PatientVisitTemplate;

class PatientVisitTemplateRepository implements Repository
{
    public function get(int $id)
    {
        return PatientVisitTemplate::find($id);
    }

    public function all()
    {
        return PatientVisitTemplate::all();
    }

    public function store(array $data): PatientVisitTemplate
    {
        return PatientVisitTemplate::create($data);
    }

    public function update(array $data, int $id)
    {
        return PatientVisitTemplate::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        return PatientVisitTemplate::where('id', $id)->delete();
    }
}
