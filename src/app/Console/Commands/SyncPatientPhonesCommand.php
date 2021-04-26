<?php

namespace App\Console\Commands;

use App\Exceptions\SyncErrorException;
use App\Services\LogService;
use App\Services\PatientsService;
use Illuminate\Console\Command;
use Throwable;

class SyncPatientPhonesCommand extends Command
{
    /** @var string */
    protected $signature = 'sync:patient-phones';

    /** @var string */
    protected $description = 'Sync patient phones.';

    /** @var LogService */
    private LogService $logService;

    /** @var PatientsService */
    private PatientsService $patientsService;

    public function __construct(
        LogService $logService,
        PatientsService $patientsService
    )
    {
        parent::__construct();
        $this->logService = $logService;
        $this->patientsService = $patientsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $externalIds = $this->patientsService->getPatientsWithoutPhone();

        if ($externalIds) {
            $result = $this->patientsService->getRemotePatients($externalIds);

            if ($result) {
                foreach ($result as $patient) {
                    if ($this->validPhoneString($patient['phone'])) {
                        try {
                            $this->patientsService->syncPatientPhoneNumber($patient);
                        } catch (Throwable $throwable) {
                            throw new SyncErrorException('Message: ' . $throwable->getMessage() . ' in file: ' . $throwable->getFile() . ' on line ' . $throwable->getLine());
                        }
                    }
                }
            }

            $this->logService->info('Synced patient phones: ' . count($result) . ' rows.');
        }

        return true;
    }

    private function validPhoneString(?string $phone): bool
    {
        if ($phone !== null && $phone !== '' && $phone !== '-') {
            return true;
        }

        return false;
    }
}
