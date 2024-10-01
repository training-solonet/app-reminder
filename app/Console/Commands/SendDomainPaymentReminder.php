<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransaksiDomain;
use App\Models\Domain;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDomainPaymentReminder extends Command
{
    protected $signature = 'reminder:domain-payment';
    protected $description = 'Kirim pengingat email hanya untuk domain yang expired dan belum lunas.';

    public function handle()
    {
        $domains = Domain::where('tgl_expired', '<=', Carbon::now()->addDays(30))->get();

        foreach ($domains as $domain) {

            $transaksi = TransaksiDomain::where('domain_id', $domain->id)->first();

            if (!$transaksi) {

                Mail::send('emails.domain_expired_reminder', ['domain' => $domain], function ($message) use ($domain) {
                    $message->to('ifkararfian11@gmail.com')
                            ->subject('Reminder: Domain Akan Expired');
                });
            } elseif ($transaksi->status === 'belum-lunas') {

                Mail::send('emails.domain_expired_reminder', ['domain' => $domain], function ($message) use ($domain) {
                    $message->to('ifkararfian11@gmail.com')
                            ->subject('Reminder: Domain Akan Expired');
                });
            }

        }

        $this->info('Pengingat email untuk domain expired yang belum lunas telah dikirim.');
        
    }
}
