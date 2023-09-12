<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('send:checklist')->daily()->at('18:00');
        $schedule->command('send:review')->daily()->at('18:00');
        $schedule->command('reminder:booking')->daily()->at('12:00');
        $schedule->command('reminder:invoice')->daily()->at('09:00');
        $schedule->command('anonymize:users')->daily()->at('06:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
