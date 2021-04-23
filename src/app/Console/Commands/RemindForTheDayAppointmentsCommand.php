<?php

namespace App\Console\Commands;

use App\Exceptions\RemindForTheDayErrorException;
use App\Helpers\Date;
use App\Helpers\Settings;
use App\Models\Enum\AppointmentType;
use App\Models\PatientAppointment;
use App\Services\AppointmentsService;
use App\Services\DoctorsService;
use App\Services\LogService;
use App\Services\MessagesService;
use Illuminate\Console\Command;
use Throwable;

class RemindForTheDayAppointmentsCommand extends Command
{
    /** @var string */
    protected $signature = 'reminder:before-day';

    /** @var string */
    protected $description = 'Reminder before day by SMS message.';

    /** @var AppointmentsService */
    private AppointmentsService $appointmentService;

    /** @var MessagesService */
    private MessagesService $messageService;

    /** @var DoctorsService */
    private DoctorsService $doctorService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        AppointmentsService $appointmentService,
        MessagesService $messageService,
        DoctorsService $doctorService,
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
            $endDayTimestamp = Date::getTomorrowEndDayTime();

            try {
                $appointments = $this->appointmentService->getPatientsForRemind($timestamp, $endDayTimestamp);

                /** @var PatientAppointment $appointment */
                foreach ($appointments as $appointment) {
                    $history = $this->appointmentService->findPatientAppointmentHistory($appointment);

                    /** @var PatientAppointment $lastAppointment */
                    $lastAppointment = $history->first();

                    if ($this->isNeedRemind($lastAppointment)) {
                        $this->messageService->remindBeforeDay($lastAppointment);

                        if ($lastAppointment->patient->day_on_day) {
                            $this->appointmentService->addAppointmentReminder($lastAppointment);
                        }
                    }

                    $this->appointmentService->markedPatientAppointmentHistory($history);
                }
            } catch (Throwable $throwable) {
                throw new RemindForTheDayErrorException('Message: ' . $throwable->getMessage() . ' in file: ' . $throwable->getFile() . ' on line ' . $throwable->getLine());
            }

            $this->logService->info('Reminded: ' . count($appointments) . ' patients.');
        }

        return true;
    }

    public function isNeedRemind(PatientAppointment $appointment): bool
    {
        $rule = [AppointmentType::ADDING, AppointmentType::EDITING, AppointmentType::CHECK_IN, AppointmentType::SET_MARK];

        if (in_array($appointment->type, $rule, true)) {
            if ($this->doctorService->doctorIsApprove($appointment->doctor->id)) {
                if ($appointment->patient->per_day) {
                    if (strlen($appointment->patient->phone) > 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isCorrectTime(string $current): bool
    {
        $param = Settings::getParam('reminder-time-per-day');

        if (!$param) {
            return false;
        }

        $hours = explode(':', $param);

        return (int)$current > (int)$hours[0] && (int)$current < 17;
    }
}
