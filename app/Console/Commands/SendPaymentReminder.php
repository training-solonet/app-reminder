<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPaymentReminder extends Command
{
    protected $signature = 'reminder:payment';
    protected $description = 'Kirim pengingat email untuk pembayaran yang belum lunas 30 hari sebelum jatuh tempo.';

    public function handle()
    {
        $jenisPembayarans = JenisPembayaran::where('status', 'aktif')->get();

        foreach ($jenisPembayarans as $jenisPembayaran) {

            $pembayarans = Pembayaran::where('id_jenis_pembayaran', $jenisPembayaran->id)
                ->where('status_bayar', 'belum-lunas')
                ->where('tgl_bayar', '<=', Carbon::now()->addDays(30))
                ->get();

            foreach ($pembayarans as $pembayaran) {
                if ($pembayaran->status_bayar === 'belum-lunas') {
                    Mail::send('emails.payment_reminder', ['pembayaran' => $pembayaran], function ($message) use ($pembayaran) {
                        $message->to('ifkararfian11@gmail.com')
                                ->subject('Reminder: Pembayaran Akan Jatuh Tempo');
                    });
                }
            }
        }

        $this->info('Pengingat email untuk pembayaran yang belum lunas telah dikirim.');
    }
}
