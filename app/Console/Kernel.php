<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Auto-cancel expired unpaid orders (manual transfer)
        $schedule->command('orders:cancel-expired')->everyFiveMinutes();

        // Poll courier tracking (optional; requires RajaOngkir PRO)
        $schedule->command('orders:poll-tracking')->everyThirtyMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
