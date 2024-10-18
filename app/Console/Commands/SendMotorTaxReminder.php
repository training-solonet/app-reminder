<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendMotorTaxReminder extends Command
{
    protected $signature = 'reminder:motor-tax';
    protected $description = 'Kirim pengingat email jika motor belum melakukan pembayaran pajak.';

    public function handle()
    {
        $motorList = Motor::where('tanggal_pajak', '<=', Carbon::now()->addDays(30))->get();

        foreach ($motorList as $motor) {

            $transaksi = Transaksi::where('id_motor', $motor->id)
                                ->where('jenis_transaksi', 'Bayar Pajak')
                                ->first();

            if (!$transaksi) {
                Mail::send('emails.motor_tax_reminder', ['motor' => $motor], function ($message) use ($motor) {
                    $message->to('example@gmail.com')  
                            ->subject('Reminder: Pajak Motor Akan Jatuh Tempo');
                });
            }
        }

        $this->info('Pengingat pajak motor telah dikirim.');
    }
}
