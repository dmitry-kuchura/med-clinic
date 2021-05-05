<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Helpers\Date;
use App\Services\LogService;
use App\Services\VisitsService;
use Illuminate\Console\Command;
use Throwable;

class SyncLatePatientVisitsCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:latest-patient-visits';

    /** @var string */
    protected $description = 'Sync latest patient visits.';

    /** @var VisitsService */
    private VisitsService $visitsService;

    /** @var LogService */
    private LogService $logService;

    public function __construct(
        VisitsService $visitsService,
        LogService $logService
    )
    {
        parent::__construct();
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
        $ids = $this->visitsService->getLastLatePatientsVisits();

        if ($ids) {
            foreach ($ids as $id) {
                try {
                    $record = $this->visitsService->getRemoteVisitByExternalId($id);
                    $this->visitsService->markedLatePatientVisit($id);

                    $this->visitsService->sync($record);
                } catch (Throwable $throwable) {
                    throw new SyncErrorException('Message: ' . $throwable->getMessage() . ' in file: ' . $throwable->getFile() . ' on line ' . $throwable->getLine());
                }
            }
        }

        $this->logService->info('Synced late patient visits: ' . count($ids) . ' rows.');

        return true;
    }
}
