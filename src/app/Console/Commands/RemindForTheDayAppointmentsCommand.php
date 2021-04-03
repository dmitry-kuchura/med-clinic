<?php

namespace App\Console\Commands;

use App\Exceptions\RemindForTheDayErrorException;
use App\Services\AppointmentService;
use App\Services\MessageService;
use Illuminate\Console\Command;
use Throwable;

class RemindForTheDayAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'reminder:before-day';

    /** @var string */
    protected $description = 'Reminder before day by SMS message.';

    /** @var AppointmentService */
    private AppointmentService $appointmentService;

    /** @var MessageService */
    private MessageService $messageService;

    public function __construct(
        AppointmentService $appointmentService,
        MessageService $messageService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->messageService = $messageService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $appointment = $this->appointmentService->getFirstPatientsAppointment();

        $history = $this->appointmentService->findPatientAppointmentHistory($appointment);

        try {
            $patientsAppointment = $history->first();

            $this->messageService->remindBeforeDay($patientsAppointment);

            $this->appointmentService->addAppointmentReminder($patientsAppointment);

            $this->appointmentService->markedPatientAppointmentHistory($history);
        } catch (Throwable $throwable) {
            throw new RemindForTheDayErrorException();
        }

        return true;
    }
}
