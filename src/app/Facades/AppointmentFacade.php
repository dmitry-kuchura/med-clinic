<?php

namespace App\Facades;

use App\Models\PatientAppointment;
use App\Repositories\PatientsAppointmentsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AppointmentFacade implements Facade
{
    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $patientsAppointmentsRepository;

    public function __construct(PatientsAppointmentsRepository $patientsAppointmentsRepository)
    {
        $this->patientsAppointmentsRepository = $patientsAppointmentsRepository;
    }

    public function list(int $id)
    {
        return $this->patientsAppointmentsRepository->paginate($id, self::RECORDS_AT_PAGE);
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
            'type' => $data['type'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
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

    public function findHistory(PatientAppointment $patientAppointment): Collection
    {
        $patient = $patientAppointment->patient->id;
        $doctor = $patientAppointment->doctor->id;
        $date_from = Carbon::parse($patientAppointment->appointment_at)->subDays(2)->format('Y-m-d 00:00:00');
        $date_to = Carbon::parse($patientAppointment->appointment_at)->addDays(2)->format('Y-m-d 23:59:59');

        return $this->patientsAppointmentsRepository->findHistory($patient, $doctor, $date_from, $date_to);
    }

    public function markedWithHistory(Collection $patientAppointments)
    {
        $ids = [];

        /** @var PatientAppointment $appointment */
        foreach ($patientAppointments as $appointment) {
            $ids[] = $appointment->id;
        }

        $this->patientsAppointmentsRepository->markedWithHistory($ids);
    }
}
