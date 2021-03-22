<?php

namespace App\Services;

use App\Actions\PatientAction;
use App\Models\Firebird\Appointment;
use App\Repositories\PatientsAppointmentsRepository;
use Illuminate\Support\Carbon;

class AppointmentService
{
    /** @var PatientsAppointmentsRepository */
    private PatientsAppointmentsRepository $repository;

    /** @var PatientAction */
    private PatientAction $action;

    public function __construct(
        PatientsAppointmentsRepository $patientsAppointmentsRepository,
        PatientAction $patientAction
    )
    {
        $this->repository = $patientsAppointmentsRepository;
        $this->action = $patientAction;
    }

    public function getLastAppointmentPatient(): ?string
    {
        $patient = $this->repository->getLastPatient();

        if (!$patient) {
            return null;
        }

        return Carbon::parse($patient->appointment_at)->format('Y-m-d H:i:s');
    }

    public function getPatientsListForMessages($timestamp): ?array
    {
        $data = [];

        $records = Appointment::select('APPOINTMENT_LOG.TIMESTART', 'APPOINTMENT_LOG.PAT_NR')
            ->with('patient')
            ->where('APPOINTMENT_LOG.TIMESTART', '>', $timestamp)
            ->limit(10)
            ->orderBy('NR', 'DESC')
            ->get();

        /** @var Appointment $record */
        foreach ($records as $record) {
            $data[] = [
                'first_name' => $record->patient->human->FIRSTNAME,
                'last_name' => $record->patient->human->SURNAME,
                'middle_name' => $record->patient->human->SECNAME,
                'gender' => $record->patient->human->SEX === 1 ? 'male' : 'female',
                'birthday' => $record->patient->human->DOB,
                'phone' => $record->patient->human->PHONE ?? null,
                'address' => $record->patient->human->LIVEADDRESS ?? null,
                'appointment_time' => $record->TIMESTART ?? null,
            ];
        }

        return $data;
    }

    public function syncPatients(array $patients): void
    {
        foreach ($patients as $patient) {
            $this->action->create($patient);
        }
    }

    private function checkNeedMessages()
    {

    }
}
