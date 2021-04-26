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
        Commands\SyncPatientVisitsCommand::class,
        Commands\RemindForTheDayAppointmentsCommand::class,
        Commands\RemindDayOnDayAppointmentsCommand::class,
        Commands\RemindForVisitDataCommand::class,
        Commands\UpdateMessageStatusCommand::class,
        Commands\SyncPatientPhonesCommand::class,
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
            // Sync
            $schedule->command('update:messages-status')->everyMinute();
            $schedule->command('sync:patient-phones')->everyFiveMinutes();
            $schedule->command('sync:appointments')->everyTenMinutes();
            $schedule->command('sync:patient-visits')->everyTenMinutes();
            // Remind
            $schedule->command('reminder:before-day')->everyMinute();
//            $schedule->command('reminder:day-on-day')->everyFiveMinutes();
//            $schedule->command('reminder:patients-data')->everyFifteenMinutes();
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
