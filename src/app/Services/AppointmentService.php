<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Firebird\Appointment;
use App\Models\Patient;
use App\Models\PatientAppointment;
use App\Repositories\Firebird\AppointmentRepository;
use App\Repositories\PatientAppointmentReminderRepository;
use App\Repositories\PatientsAppointmentsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AppointmentService
{
    const RECORDS_AT_PAGE = 30;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $patientsAppointmentsRepository;

    private PatientAppointmentReminderRepository $patientsAppointmentsReminderRepository;

    public function __construct(
        AppointmentRepository $appointmentRepository,
        PatientsAppointmentsRepository $patientsAppointmentsRepository,
        PatientAppointmentReminderRepository $patientsAppointmentsReminderRepository
    )
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->patientsAppointmentsRepository = $patientsAppointmentsRepository;
        $this->patientsAppointmentsReminderRepository = $patientsAppointmentsReminderRepository;
    }

    public function list(int $id)
    {
        return $this->patientsAppointmentsRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    public function getLastPatientsAppointment(): ?PatientAppointment
    {
        return $this->patientsAppointmentsRepository->getLastPatient();
    }

    public function getPatientsForRemind(string $timestamp): ?Collection
    {
        return $this->patientsAppointmentsRepository->getPatientsForRemind($timestamp);
    }

    public function getPatientsListForSync(string $timestamp, ?int $external = null): ?array
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

            $appointmentData = [
                'appointment_at' => $data['appointment_time'],
                'comment' => $data['comment'] ?? null,
                'doctor_name' => $data['doctor_name'] ?? null,
                'type' => $data['type'],
                'patient_id' => $data['patient_id'],
                'doctor_id' => $data['doctor_id'],
                'external_id' => $data['external_id'],
            ];

            $this->patientsAppointmentsRepository->store($appointmentData);
        }
    }

    public function findPatientAppointmentHistory(PatientAppointment $appointment): Collection
    {
        $patient = $appointment->patient->id;
        $doctor = $appointment->doctor->id;
        $date_from = Carbon::parse($appointment->appointment_at)->subDays(2)->format('Y-m-d 00:00:00');
        $date_to = Carbon::parse($appointment->appointment_at)->addDays(2)->format('Y-m-d 23:59:59');

        return $this->patientsAppointmentsRepository->findHistory($patient, $doctor, $date_from, $date_to);
    }

    public function markedPatientAppointmentHistory(Collection $appointments)
    {
        $ids = [];

        /** @var PatientAppointment $appointment */
        foreach ($appointments as $appointment) {
            $ids[] = $appointment->id;
        }

        $this->patientsAppointmentsRepository->markedWithHistory($ids);
    }

    public function addAppointmentReminder(PatientAppointment $appointment)
    {
        $data = [];

        $data['appointment_at'] = Carbon::parse($appointment->appointment_at)->format('Y-m-d H:i:s');
        $data['patient_id'] = $appointment->patient_id;

        $this->patientsAppointmentsReminderRepository->store($data);
    }
}
