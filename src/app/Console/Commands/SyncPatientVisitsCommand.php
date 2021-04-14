<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Helpers\Date;
use App\Services\AppointmentsService;
use App\Services\DoctorsService;
use App\Services\LogService;
use App\Services\PatientsService;
use App\Services\VisitsService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

class SyncPatientVisitsCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:patient-visits';

    /** @var string */
    protected $description = 'Sync patient visits.';

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
        $lastPatientVisit = $this->visitsService->getLastPatientsVisits();

        if ($lastPatientVisit) {
            $external = $lastPatientVisit->external_id;
            $result = $this->visitsService->getRemoteVisits($external, null);
        } else {
            $timestamp = Date::getMorningTime();
            $result = $this->visitsService->getRemoteVisits(null, $timestamp);
        }

        foreach ($result as $record) {
            try {
                $this->visitsService->sync($record);
            } catch (Throwable $throwable) {
                throw new SyncErrorException($throwable->getMessage());
            }
        }

        $this->logService->info('Synced patient visits: ' . count($result) . ' rows.');

        return true;
    }
}
