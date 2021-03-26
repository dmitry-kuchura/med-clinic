<?php

namespace App\Services;

use App\Models\Firebird\Appointment;
use App\Models\PatientsAppointments;
use App\Repositories\Firebird\AppointmentRepository;
use App\Repositories\PatientsAppointmentsRepository;

class AppointmentService
{
    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $repository;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    public function __construct(
        PatientsAppointmentsRepository $patientsAppointmentsRepository,
        AppointmentRepository $appointmentRepository
    )
    {
        $this->repository = $patientsAppointmentsRepository;
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getLastPatientsAppointment(): ?PatientsAppointments
    {
        return $this->repository->getLastPatient();
    }

    public function getPatientsListForMessages(string $timestamp, ?int $external = null): ?array
    {
        $data = [];

        $records = $this->appointmentRepository->lastAppointment($timestamp, $external);

        /** @var Appointment $record */
        foreach ($records as $record) {
            $data[] = [
                'first_name' => $record->patient->human->FIRSTNAME,
                'last_name' => $record->patient->human->SURNAME,
                'middle_name' => $record->patient->human->SECNAME,
                'gender' => $record->patient->human->SEX === 1 ? 'male' : 'female',
                'birthday' => $record->patient->human->DOB ?? null,
                'phone' => $record->patient->human->PHONE ?? null,
                'address' => $record->patient->human->LIVEADDRESS ?? null,

                'appointment_time' => $record->TIMESTART ?? null,
                'external_id' => $record->NR ?? null,
                'doctor_name' => $record->STAFFFIO ?? null,
                'comment' => $record->COMMENT ?? null,
            ];
        }

        return $data;
    }
}
