<?php

namespace App\Repositories;

use App\Models\PatientVisitTemplate;

class PatientVisitTemplateRepository implements Repository
{
    public function find(string $template): ?PatientVisitTemplate
    {
        return PatientVisitTemplate::where('template', $template)->first();
    }

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

    public function delete(string $template)
    {
        return PatientVisitTemplate::where('template', $template)->delete();
    }

    public function destroy(int $id)
    {
        return PatientVisitTemplate::where('id', $id)->delete();
    }
}
