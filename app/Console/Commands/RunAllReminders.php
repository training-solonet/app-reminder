<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAllReminders extends Command
{
    protected $signature = 'reminders:run-all-reminder';
    protected $description = 'Jalankan semua reminder';

    public function handle()
    {
        $this->call('reminder:domain-payment');
        $this->call('reminder:bts-payment');
        $this->call('reminder:motor-tax');
        $this->call('reminder:payment');
    }
}