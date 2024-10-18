<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Bts;
use App\Models\Domain;
use App\Models\Pembayaran;
use App\Models\Reminder;
use App\Models\JenisPembayaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $domains = Domain::where('tgl_expired', '<=', $now->addDays(30))
                         ->orderBy('tgl_expired', 'asc')
                         ->get();
        $countBelumTerbayar = $domains->count();

        $bts = Bts::where('jatuh_tempo', '<=', $now->addDays(30))
                  ->orderBy('jatuh_tempo', 'asc')
                  ->get();
        $countBtsJatuhTempo = $bts->count();

        $pembayarans = Pembayaran::where('status_bayar', 'belum-lunas')->get();
        $countPembayaranBelumLunas = $pembayarans->count();

        $motorPajakJatuhTempo = Motor::where('tanggal_pajak', '<=', $now->addDays(30))
                                     ->orderBy('tanggal_pajak', 'asc')
                                     ->get();
        $countMotorPajakJatuhTempo = $motorPajakJatuhTempo->count();

        $activeReminders = Reminder::where('status', 'aktif')->count();
        $pendingReminders = Reminder::where('status_pelaksanaan', 'belum')->count();

        $jenisPembayaranJatuhTempo = JenisPembayaran::where('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(7))->orderBy('tanggal_jatuh_tempo')->get();
        $countJenisPembayaranJatuhTempo = $jenisPembayaranJatuhTempo->count();

        return view('dashboard', compact(
            'countBelumTerbayar',
            'countBtsJatuhTempo',
            'countPembayaranBelumLunas',
            'countMotorPajakJatuhTempo',
            'activeReminders',
            'pendingReminders',
            'countJenisPembayaranJatuhTempo'
        ));
    }
}
