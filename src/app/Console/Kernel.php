<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SyncAppointmentsCommand::class,
        Commands\RemindForTheDayAppointmentsCommand::class,
        Commands\RemindDayOnDayAppointmentsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (App::environment('production')) {
            $schedule->command('sync:appointments')->everyFiveMinutes();
            $schedule->command('reminder:before-day')->everyTenMinutes();
            $schedule->command('reminder:day-on-day')->everyFifteenMinutes();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
