<?php

namespace App\Console\Commands;

use App\Helpers\TurboSMS;
use App\Services\AppointmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendSmsCommand extends Command
{
    /** @var string */
    protected $signature = 'sms:send';

    /** @var string */
    protected $description = 'Scheduler send SMS messages.';

    private AppointmentService $services;

    private TurboSMS $smsSender;

    public function __construct(AppointmentService $appointmentService)
    {
        parent::__construct();
        $this->smsSender = new TurboSMS();
        $this->services = $appointmentService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->checkIsAllowedSend()) {
            $lastAppointment = $this->services->getLastAppointmentPatient();

            if ($lastAppointment) {
                $result = $this->services->getPatientsListForMessages($lastAppointment);
            } else {
                $appointment = Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
                $result = $this->services->getPatientsListForMessages($appointment);
            }

            foreach ($result as $record) {
                if (isset($record['phone'])) {
                    $message = $this->prepareMessage($record);
//                    $this->smsSender->send([$record['phone']], $message);
                    $this->smsSender->send(['0931106215'], $message);
                }
            }

            $this->services->syncPatients($result);
        }

        return true;
    }

    private function checkIsAllowedSend(): bool
    {
        $now = (int)Carbon::now('Europe/Kiev')->format('H');

        return $now > 06 && $now < 21;
    }

    private function prepareMessage(array $record): string
    {
        $message = 'Ви записані на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}';

        $datetime = Carbon::parse($record['appointment_time']);

        return str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $message);
    }
}
