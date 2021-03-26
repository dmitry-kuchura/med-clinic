<?php

namespace App\Services;

use App\Facades\AppointmentFacade;
use App\Models\Firebird\Appointment;
use App\Models\Patient;
use App\Models\PatientAppointment;
use App\Repositories\Firebird\AppointmentRepository;
use App\Repositories\PatientsAppointmentsRepository;
use Illuminate\Support\Carbon;

class AppointmentService
{
    /** @var AppointmentFacade */
    private AppointmentFacade $appointmentFacade;

    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $repository;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    public function __construct(
        AppointmentFacade $appointmentFacade,
        PatientsAppointmentsRepository $patientsAppointmentsRepository,
        AppointmentRepository $appointmentRepository
    )
    {
        $this->repository = $patientsAppointmentsRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->appointmentFacade = $appointmentFacade;
    }

    public function getLastPatientsAppointment(): ?PatientAppointment
    {
        return $this->repository->getLastPatient();
    }

    public function getPatientsListForMessages(string $timestamp, ?int $external = null): ?array
    {
        $end = Carbon::parse($timestamp)->setHours(23)->setMinutes(59)->setSeconds(59)->format('Y-m-d H:i:s');

        $data = [];

        $records = $this->appointmentRepository->lastAppointment($timestamp, $end, $external);

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

    public function syncAppointment(array $data, Patient $patient)
    {
        if ($patient) {
            $data['patient_id'] = $patient->id;
            $this->appointmentFacade->create($data);
        }
    }
}
