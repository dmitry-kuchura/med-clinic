<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\RemindForVisitDataCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var RemindForVisitDataCommand */
    private RemindForVisitDataCommand $service;

    public function __construct(RemindForVisitDataCommand $service)
    {
        $this->service = $service;
    }

    public function data()
    {
        $this->service->handle();
    }
}
