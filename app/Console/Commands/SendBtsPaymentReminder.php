<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bts;
use App\Models\TransaksiBts;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBtsPaymentReminder extends Command
{
    protected $signature = 'reminder:bts-payment';
    protected $description = 'Kirim pengingat email hanya untuk BTS yang expired dan belum lunas.';

    public function handle()
    {

        $btsList = Bts::where('jatuh_tempo', '<=', Carbon::now()->addDays(30))->get();

        foreach ($btsList as $bts) {

            $transaksi = TransaksiBts::where('bts_id', $bts->id)->first();

            if (!$transaksi) {
                Mail::send('emails.bts_expired_reminder', ['bts' => $bts], function ($message) use ($bts) {
                    $message->to('ifkararfian11@gmail.com')
                            ->subject('Reminder: BTS Akan Expired');
                });

            } elseif ($transaksi->status === 'belum_lunas') {
                Mail::send('emails.bts_expired_reminder', ['bts' => $bts], function ($message) use ($bts) {
                    $message->to('ifkararfian11@gmail.com')
                            ->subject('Reminder: BTS Akan Expired');
                });
            }
        }

        $this->info('Pengingat email untuk BTS yang expired dan belum lunas telah dikirim.');
    }
}
