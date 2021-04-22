<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\RemindForTheDayAppointmentsCommand;
use App\Http\Controllers\Controller;

class FirebirdController extends Controller
{
    /** @var RemindForTheDayAppointmentsCommand */
    private RemindForTheDayAppointmentsCommand $command;

    public function __construct(RemindForTheDayAppointmentsCommand $command)
    {
        $this->command = $command;
    }

    public function data()
    {
        $this->command->handle();
    }
}
