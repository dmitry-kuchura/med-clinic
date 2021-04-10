<?php

namespace App\Console\Commands;

use App\Exceptions\RemindForTheDayErrorException;
use App\Helpers\Date;
use App\Helpers\Settings;
use App\Models\PatientAppointment;
use App\Services\AppointmentService;
use App\Services\DoctorService;
use App\Services\LogService;
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

    /** @var DoctorService */
    private DoctorService $doctorService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        AppointmentService $appointmentService,
        MessageService $messageService,
        DoctorService $doctorService,
        LogService $logService
    )
    {
        parent::__construct();
        $this->appointmentService = $appointmentService;
        $this->messageService = $messageService;
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
        if ($this->isCorrectTime(Date::getCurrentHour())) {
            $timestamp = Date::getTomorrowMorningTime();

            try {
                $appointments = $this->appointmentService->getPatientsForRemind($timestamp);

                foreach ($appointments as $appointment) {
                    $history = $this->appointmentService->findPatientAppointmentHistory($appointment);

                    /** @var PatientAppointment $lastAppointment */
                    $lastAppointment = $history->first();

                    if (!$this->doctorService->doctorIsExcluded($lastAppointment->doctor_id)) {
                        if ($lastAppointment->patient->per_day) {
                            $this->messageService->remindBeforeDay($lastAppointment);

                            if ($lastAppointment->patient->day_on_day) {
                                $this->appointmentService->addAppointmentReminder($lastAppointment);
                            }
                        }
                    }

                    $this->appointmentService->markedPatientAppointmentHistory($history);
                }
            } catch (Throwable $throwable) {
                throw new RemindForTheDayErrorException();
            }

            $this->logService->info('Reminded: ' . count($appointments) . ' patients.');
        }

        return true;
    }

    public function isCorrectTime(string $current): bool
    {
        $param = Settings::getParam('reminder-time-per-day');

        if (!$param) {
            return false;
        }

        $hours = explode(':', $param);

        return (int)$current > (int)$hours[0];
    }
}
