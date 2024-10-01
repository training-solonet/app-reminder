<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendDomainPaymentReminder::class,
        Commands\SendBtsPaymentReminder::class,
        Commands\SendMotorTaxReminder::class,
        Commands\SendPaymentReminder::class,
        Commands\RunAllReminders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reminder:domain-payment')->dailyAt('00:00');
        $schedule->command('reminder:bts-payment')->dailyAt('00:00');
        $schedule->command('reminder:motor-tax')->dailyAt('00:00');
        $schedule->command('reminder:payment')->dailyAt('00:00');

        $hour = config('app.hour');
        $min = config('app.min');
        $scheduledInterval = $hour !== '' ? ( ($min !== '' && $min != 0) ?  $min .' */'. $hour .' * * *' : '0 */'. $hour .' * * *') : '*/'. $min .' * * * *';
        if(env('IS_DEMO')) {
            $schedule->command('migrate:fresh --seed')->cron($scheduledInterval);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
