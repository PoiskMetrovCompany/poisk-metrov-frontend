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
        if (config('app.backups_enabled')) {
            $schedule->command('backup:run --only-db')->daily();
        }

        $schedule->command('app:refresh-feed-with-full-update')->daily();
        $schedule->command('app:update-users-in-crm')->everyFifteenMinutes();
        $schedule->command('app:cache-all')->everyFifteenMinutes();
        $schedule->command('app:clean-old-sessions --diff=5')->everyFiveMinutes();
        $schedule->command('app:refresh-managers-with-full-update')->hourly();
        // $schedule->command('app:full-create-bank-tariffs')->daily();
        $schedule->command('app:update-telegram-deal-bot')->daily();
        $schedule->command('app:clean-up-gallery')->daily();

        $schedule->command('app:backup')
            ->weeklyOn(1, '11:00')
            ->timezone('Russia/Novosibirsk');

//        $schedule->command('app:parser-cbr')
//            ->quarterly()
//            ->timezone('Russia/Novosibirsk');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
