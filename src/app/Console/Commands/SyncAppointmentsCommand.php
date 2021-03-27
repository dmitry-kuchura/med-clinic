<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Services\AppointmentService;
use App\Services\DoctorService;
use App\Services\MessageService;
use App\Services\PatientService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

class SyncAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:appointments';

    /** @var string */
    protected $description = 'Scheduler send SMS messages.';

    /** @var AppointmentService */
    private AppointmentService $appointmentService;

    /** @var PatientService */
    private PatientService $patientService;

    /** @var DoctorService */
    private DoctorService $doctorService;

    /** @var MessageService */
    private MessageService $messageService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientService $patientService,
        DoctorService $doctorService,
        MessageService $messageService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->patientService = $patientService;
        $this->doctorService = $doctorService;
        $this->messageService = $messageService;
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

            $result = $this->appointmentService->getPatientsListForMessages($timestamp, $external);
        } else {
            $appointment = $this->getCurrentTime();
            $result = $this->appointmentService->getPatientsListForMessages($appointment);
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

        return true;
    }

    private function getCurrentTime(): string
    {
        return Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }

    private function prepareMessage(array $record): string
    {
        $message = 'Ви записані на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}';

        $datetime = Carbon::parse($record['appointment_time']);

        return str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $message);
    }
}
