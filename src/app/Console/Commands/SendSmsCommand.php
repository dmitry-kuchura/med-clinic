<?php

namespace App\Console\Commands;

use App\Services\AppointmentService;
use App\Services\MessageService;
use App\Services\PatientService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

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

    /** @var MessageService */
    private MessageService $messageService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientService $patientService,
        MessageService $messageService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->patientService = $patientService;
        $this->messageService = $messageService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->checkIsAllowedSend()) {
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
                    $patient = $this->patientService->syncPatient($record);

                    $this->appointmentService->syncAppointment($record, $patient);

                    if ($patient->phone) {
                        $text = $this->prepareMessage($record);

                        $this->messageService->sendMessage([
                            'phone' => '+380930041540',
                            'patient_id' => $patient->id,
                            'text' => $text
                        ]);
                    }
                } catch (Throwable $throwable) {

                }
            }
        }

        return true;
    }

    private function checkIsAllowedSend(): bool
    {
        $now = (int)Carbon::now()->format('H');

        return $now > 07 && $now < 21;
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
