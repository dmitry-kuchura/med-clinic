<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\SyncPatientVisitsCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var SyncPatientVisitsCommand */
    private SyncPatientVisitsCommand $command;

    public function __construct(SyncPatientVisitsCommand $command)
    {
        $this->command = $command;
    }

    public function data()
    {
        $this->command->handle();
    }
}
