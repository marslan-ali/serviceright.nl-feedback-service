<?php

namespace App\Console;

use App\Console\Commands\ImportOldFeedback;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Laravelista\LumenVendorPublish\VendorPublishCommand as VendorPublishCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ImportOldFeedback::class,
        VendorPublishCommand::class,
    ];

    /**
     * Import the old feed backs in the system.
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('import:old-feedback')->hourly();
    }
}
