<?php

namespace App\Console\Commands;

use App\Exceptions\RemindForTheDayErrorException;
use App\Helpers\Date;
use App\Models\PatientVisit;
use App\Services\DoctorsService;
use App\Services\LogService;
use App\Services\MessagesService;
use App\Services\VisitsService;
use Illuminate\Console\Command;
use Throwable;

class RemindForVisitDataCommand extends Command
{
    /** @var string */
    protected $signature = 'reminder:patients-data';

    /** @var string */
    protected $description = 'Reminder for added patients data by SMS message.';

    /** @var MessagesService */
    private MessagesService $messageService;

    /** @var VisitsService */
    private VisitsService $visitsService;

    /** @var DoctorsService */
    private DoctorsService $doctorsService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        MessagesService $messageService,
        VisitsService $visitsService,
        DoctorsService $doctorsService,
        LogService $logService
    )
    {
        parent::__construct();
        $this->messageService = $messageService;
        $this->visitsService = $visitsService;
        $this->doctorsService = $doctorsService;
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
            $timestamp = Date::getMorningTime();

            try {
                $visits = $this->visitsService->getListForRemind($timestamp);

                /** @var PatientVisit $visit */
                foreach ($visits as $visit) {
                    if ($this->doctorsService->doctorIsApprove($visit->doctor_id)) {
                        $this->messageService->remindNewAnalyse($visit);
                    }

                    $this->visitsService->markedVisit($visit);
                }
            } catch (Throwable $throwable) {
                throw new RemindForTheDayErrorException();
            }

            $this->logService->info('Sent: ' . count($visits) . ' reminders about visits.');
        }

        return true;
    }

    public function isCorrectTime(string $current): bool
    {
        return (int)$current > 9 ?? (int)$current < 18;
    }
}
