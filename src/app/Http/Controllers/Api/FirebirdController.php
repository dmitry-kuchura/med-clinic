<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\RemindDayOnDayAppointmentsCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var RemindDayOnDayAppointmentsCommand */
    private RemindDayOnDayAppointmentsCommand $service;

    public function __construct(RemindDayOnDayAppointmentsCommand $service)
    {
        $this->service = $service;
    }

    public function data()
    {
        $this->service->handle();
    }
}
