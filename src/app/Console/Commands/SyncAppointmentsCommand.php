<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Services\AppointmentService;
use App\Services\DoctorService;
use App\Services\LogService;
use App\Services\PatientService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

class SyncAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:appointments';

    /** @var string */
    protected $description = 'Sync appointments.';

    /** @var AppointmentService */
    private AppointmentService $appointmentService;

    /** @var PatientService */
    private PatientService $patientService;

    /** @var DoctorService */
    private DoctorService $doctorService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientService $patientService,
        DoctorService $doctorService,
        LogService $logService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->patientService = $patientService;
        $this->doctorService = $doctorService;
        $this->logService = $logService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastAppointment = $this->appointmentService->getLastPatientsAppointment();

        if ($lastAppointment) {
            $timestamp = Carbon::parse($lastAppointment->appointment_at)->format('Y-m-d H:i:s');
            $external = $lastAppointment->external_id;

            $result = $this->appointmentService->getPatientsListForSync($timestamp, $external);
        } else {
            $timestamp = $this->getCurrentTime();
            $result = $this->appointmentService->getPatientsListForSync($timestamp);
        }

        foreach ($result as $record) {
            try {
                $patient = $this->patientService->syncPatient($record['patient']);
                $doctor = $this->doctorService->syncDoctor($record['doctor']);

                $this->appointmentService->syncAppointment($record['appointment'], $patient, $doctor);
            } catch (Throwable $throwable) {
                throw new SyncErrorException();
            }
        }

        $this->logService->info('Synced: ' . count($result) . ' rows.');

        return true;
    }

    private function getCurrentTime(): string
    {
        return Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }
}
