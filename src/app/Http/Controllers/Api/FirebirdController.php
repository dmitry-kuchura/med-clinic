<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\RemindDayOnDayAppointmentsCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var RemindDayOnDayAppointmentsCommand */
    private RemindDayOnDayAppointmentsCommand $command;

    public function __construct(RemindDayOnDayAppointmentsCommand $command)
    {
        $this->command = $command;
    }

    public function data()
    {
        $this->command->handle();
    }
}
