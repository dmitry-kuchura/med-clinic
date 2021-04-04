<?php

namespace App\Console\Commands;

use App\Helpers\Date;
use App\Services\AppointmentService;
use App\Services\MessageService;
use App\Services\PatientService;
use Illuminate\Console\Command;

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
        if ((int)Date::getCurrentHour() > 9 && (int)Date::getCurrentHour() < 21) {

        }

        return true;
    }
}
