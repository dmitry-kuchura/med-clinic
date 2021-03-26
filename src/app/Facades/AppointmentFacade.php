<?php

namespace App\Facades;

use App\Repositories\PatientsAppointmentsRepository;

class AppointmentFacade implements Facade
{
    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $patientsAppointmentsRepository;

    public function __construct(PatientsAppointmentsRepository $patientsAppointmentsRepository)
    {
        $this->patientsAppointmentsRepository = $patientsAppointmentsRepository;
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }

    public function create(array $data): void
    {
        $patientsAppointmentData = [
            'appointment_at' => $data['appointment_time'],
            'comment' => $data['comment'] ?? null,
            'doctor_name' => $data['doctor_name'] ?? null,
            'patient_id' => $data['patient_id'],
            'external_id' => $data['external_id'],
        ];

        $this->patientsAppointmentsRepository->store($patientsAppointmentData);
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}
