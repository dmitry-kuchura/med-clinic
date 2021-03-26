<?php

namespace App\Services;

use App\Facades\PatientFacade;
use App\Helpers\PhoneNumber;
use App\Models\Patient;

class PatientService
{
    /** @var PatientFacade */
    private PatientFacade $patientFacade;

    public function __construct(PatientFacade $patientFacade)
    {
        $this->patientFacade = $patientFacade;
    }

    public function list()
    {
        return $this->patientFacade->list();
    }

    public function find(int $id): ?Patient
    {
        return $this->patientFacade->find($id);
    }

    public function update(array $data): void
    {
        $this->patientFacade->update($data);
    }

    public function create(array $data): ?Patient
    {
        return $this->patientFacade->create($data);
    }

    public function syncPatient(array $data): ?Patient
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        return $this->patientFacade->create($data);
    }
}
