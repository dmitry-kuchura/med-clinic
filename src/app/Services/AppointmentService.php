<?php

namespace App\Services;

use App\Facades\AppointmentFacade;
use App\Models\Doctor;
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
    private PatientsAppointmentsRepository $patientsAppointmentsRepository;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    public function __construct(
        AppointmentFacade $appointmentFacade,
        PatientsAppointmentsRepository $patientsAppointmentsRepository,
        AppointmentRepository $appointmentRepository
    )
    {
        $this->patientsAppointmentsRepository = $patientsAppointmentsRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->appointmentFacade = $appointmentFacade;
    }

    public function getLastPatientsAppointment(): ?PatientAppointment
    {
        return $this->patientsAppointmentsRepository->getLastPatient();
    }

    public function getPatientsListForMessages(string $timestamp, ?int $external = null): ?array
    {
        $data = [];

        $records = $this->appointmentRepository->lastAppointment($timestamp, $external);

        /** @var Appointment $record */
        foreach ($records as $record) {
            $data[] = [
                'patient' => [
                    'first_name' => $record->patient->human->FIRSTNAME,
                    'last_name' => $record->patient->human->SURNAME,
                    'middle_name' => $record->patient->human->SECNAME,
                    'gender' => $record->patient->human->SEX === 1 ? 'male' : 'female',
                    'birthday' => $record->patient->human->DOB ?? null,
                    'phone' => $record->patient->human->PHONE ?? $record->patient->human->MOBPHONE,
                    'external_id' => $record->patient->NR,
                ],
                'doctor' => [
                    'first_name' => $record->doctor->human->FIRSTNAME,
                    'last_name' => $record->doctor->human->SURNAME,
                    'middle_name' => $record->doctor->human->SECNAME,
                    'gender' => $record->doctor->human->SEX === 1 ? 'male' : 'female',
                    'birthday' => $record->doctor->human->DOB ?? null,
                    'phone' => $record->doctor->human->PHONE ?? $record->doctor->human->MOBPHONE,
                    'external_id' => $record->doctor->NR,
                ],
                'appointment' => [
                    'appointment_time' => $record->TIMESTART ?? null,
                    'doctor_name' => $record->STAFFFIO ?? null,
                    'comment' => $record->COMMENT ?? null,
                    'type' => $record->OPTYPE_NR,
                    'external_id' => $record->NR ?? null,
                ],
            ];
        }

        return $data;
    }

    public function syncAppointment(array $data, Patient $patient, Doctor $doctor)
    {
        if ($patient && $doctor) {
            $data['patient_id'] = $patient->id;
            $data['doctor_id'] = $doctor->id;

            $this->appointmentFacade->create($data);
        }
    }
}
