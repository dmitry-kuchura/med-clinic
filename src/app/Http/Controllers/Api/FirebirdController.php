<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\SyncPatientPhonesCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var SyncPatientPhonesCommand */
    private SyncPatientPhonesCommand $service;

    public function __construct(SyncPatientPhonesCommand $service)
    {
        $this->service = $service;
    }

    public function data()
    {
        $this->service->handle();
    }
}
