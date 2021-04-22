<?php

namespace App\Console\Commands;

use App\Exceptions\RemindForTheDayErrorException;
use App\Helpers\Date;
use App\Services\AppointmentsService;
use App\Services\MessagesService;
use Illuminate\Console\Command;
use Throwable;

class RemindDayOnDayAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'reminder:day-on-day';

    /** @var string */
    protected $description = 'Reminder day on day by SMS message.';

    /** @var AppointmentsService */
    private AppointmentsService $appointmentService;

    /** @var MessagesService */
    private MessagesService $messageService;

    public function __construct(
        AppointmentsService $appointmentService,
        MessagesService $messageService
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
        if ((int)Date::getCurrentHour() > 8 && (int)Date::getCurrentHour() < 21) {
            try {
                $timestamp = Date::getMorningTime();
                $endDayTimestamp = Date::getEndDayTime();

                $reminders = $this->appointmentService->getReminders($timestamp, $endDayTimestamp);

                foreach ($reminders as $reminder) {
                    $this->messageService->remindDayOnDay($reminder);
                    $this->appointmentService->markedPatientAppointmentReminder($reminder);
                }
            } catch (Throwable $throwable) {
                throw new RemindForTheDayErrorException('Message: ' . $throwable->getMessage() . ' in file: ' . $throwable->getFile() . ' on line ' . $throwable->getLine());
            }
        }

        return true;
    }
}
