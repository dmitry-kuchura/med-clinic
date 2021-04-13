<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\RemindForTheDayAppointmentsCommand;
use App\Http\Controllers\Controller;
use App\TurboSMS\Service;

class FirebirdController extends Controller
{
    /** @var RemindForTheDayAppointmentsCommand */
    private RemindForTheDayAppointmentsCommand $command;

    /** @var Service */
    private Service $service;

    public function __construct(RemindForTheDayAppointmentsCommand $command, Service $service)
    {
        $this->command = $command;
        $this->service = $service;
    }

    public function list()
    {
        dd($this->service->sendMessage(['+380931106215'], 'Hello from PHP'));
    }
}
