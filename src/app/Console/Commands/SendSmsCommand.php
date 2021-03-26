<?php

namespace App\Console\Commands;

use App\Services\AppointmentService;
use App\Services\PatientService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendSmsCommand extends Command
{
    /** @var string */
    protected $signature = 'sms:send';

    /** @var string */
    protected $description = 'Scheduler send SMS messages.';

    /** @var AppointmentService */
    private AppointmentService $appointmentService;

    /** @var PatientService */
    private PatientService $patientService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientService $patientService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->patientService = $patientService;
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
            $patient = $this->patientService->syncPatient($record);

            if ($patient->phone) {
                $text = $this->prepareMessage($record);

                $this->patientService->sendMessage([
//                    'phone' => $patient->phone,
                    'phone' => '+380931106215',
                    'patient_id' => $patient->id,
                    'text' => $text
                ]);
            }
        }

        return true;
    }

    private function checkIsAllowedSend(): bool
    {
        $now = (int)Carbon::now('Europe/Kiev')->format('H');

        return $now > 06 && $now < 21;
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
