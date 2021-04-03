<?php

namespace App\Console\Commands;

use App\Services\AppointmentService;
use App\Services\MessageService;
use App\Services\PatientService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RemindDayOnDayAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'reminder:day-on-day';

    /** @var string */
    protected $description = 'Reminder day on day by SMS message.';

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


        return true;
    }

    private function getCurrentTime(): string
    {
        return Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }
}
