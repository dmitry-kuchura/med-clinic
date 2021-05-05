<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\SyncLatePatientVisitsCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var SyncLatePatientVisitsCommand */
    private SyncLatePatientVisitsCommand $service;

    public function __construct(SyncLatePatientVisitsCommand $service)
    {
        $this->service = $service;
    }

    public function data()
    {
        $this->service->handle();
    }
}
