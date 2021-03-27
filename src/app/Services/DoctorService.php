<?php

namespace App\Services;

use App\Facades\DoctorFacade;
use App\Helpers\PhoneNumber;
use App\Models\Doctor;

class DoctorService
{
    /** @var DoctorFacade */
    private DoctorFacade $doctorFacade;

    public function __construct(DoctorFacade $doctorFacade)
    {
        $this->doctorFacade = $doctorFacade;
    }

    public function list()
    {
        return $this->doctorFacade->list();
    }

    public function find(int $id): ?Doctor
    {
        return $this->doctorFacade->find($id);
    }

    public function update(array $data): void
    {
        $this->doctorFacade->update($data);
    }

    public function create(array $data): ?Doctor
    {
        return $this->doctorFacade->create($data);
    }

    public function syncDoctor(array $data): ?Doctor
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        return $this->doctorFacade->create($data);
    }
}
