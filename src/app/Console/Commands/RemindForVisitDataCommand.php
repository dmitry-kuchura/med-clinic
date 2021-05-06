<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Helpers\Date;
use App\Models\PatientVisit;
use App\Models\PatientVisitData;
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

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        MessagesService $messageService,
        VisitsService $visitsService,
        LogService $logService
    )
    {
        parent::__construct();
        $this->messageService = $messageService;
        $this->visitsService = $visitsService;
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
            $startTimestamp = Date::getYesterdayMorningTime();
            $endTimestamp = Date::getYesterdayEndDayTime();

            $added = [];

            try {
                $visits = $this->visitsService->getListForRemind($startTimestamp, $endTimestamp);

                /** @var PatientVisit $visit */
                foreach ($visits as $visit) {
                    if ($this->isNeedRemind($visit, $added)) {
                        $this->messageService->remindNewAnalyse($visit);
                        $added[] = $visit->patient->id;
                    }

                    $this->visitsService->markedVisit($visit);
                }
            } catch (Throwable $throwable) {
                throw new SyncErrorException('Message: ' . $throwable->getMessage() . ' in file: ' . $throwable->getFile() . ' on line ' . $throwable->getLine());
            }

            $this->logService->info('Sent: ' . count($visits) . ' reminders about visits.');
        }

        return true;
    }

    public function isNeedRemind(PatientVisit $visit, array $added): bool
    {
        if ($visit->patient->phone && strlen($visit->patient->phone)) {
            /** @var PatientVisitData $data */
            foreach ($visit->data as $data) {
                if (trim($data->category) === 'Лабораторное исследование') {
                    if (!in_array($visit->patient->id, $added)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isCorrectTime(string $current): bool
    {
        return (int)$current > 9 && (int)$current < 18;
    }
}
