<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Helpers\Date;
use App\Services\AppointmentsService;
use App\Services\DoctorsService;
use App\Services\LogService;
use App\Services\PatientsService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

class SyncAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:appointments';

    /** @var string */
    protected $description = 'Sync appointments.';

    /** @var AppointmentsService */
    private AppointmentsService $appointmentService;

    /** @var PatientsService */
    private PatientsService $patientService;

    /** @var DoctorsService */
    private DoctorsService $doctorService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        AppointmentsService $appointmentService,
        PatientsService $patientService,
        DoctorsService $doctorService,
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
            $timestamp = Date::getCurrentTime();
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
}
