<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Bts;
use App\Models\Pembayaran;
use App\Models\Motor;
use App\Models\Reminder;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        // Domain yang belum dibayar
        $domains = Domain::where('tgl_expired', '<=', $now->addDays(30))
                         ->orderBy('tgl_expired', 'asc')
                         ->get();
        $countBelumTerbayar = $domains->count();

        // BTS yang mendekati jatuh tempo
        $bts = Bts::where('jatuh_tempo', '<=', $now->addDays(30))
                  ->orderBy('jatuh_tempo', 'asc')
                  ->get();
        $countBtsJatuhTempo = $bts->count();

        // Pembayaran yang belum lunas
        $pembayarans = Pembayaran::where('status_bayar', 'belum-lunas')
                                 ->orderBy('tgl_bayar', 'asc')
                                 ->get();
        $countPembayaranBelumLunas = $pembayarans->count();

        // Motor yang pajaknya mendekati jatuh tempo
        $motorPajakJatuhTempo = Motor::where('tanggal_pajak', '<=', $now->addDays(30))
                                     ->orderBy('tanggal_pajak', 'asc')
                                     ->get();
        $countMotorPajakJatuhTempo = $motorPajakJatuhTempo->count();

        // Reminders that are active and pending
        $activeReminders = Reminder::where('status', 'aktif')->count();
        $pendingReminders = Reminder::where('status_pelaksanaan', 'belum')->count();

        // Query untuk count transaksi per bulan 
        $countPembayaranPerBulan = Pembayaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $countDomainPerBulan = Domain::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $countBtsPerBulan = Bts::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Combine
        $transactionCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $transactionCounts[] = ($countPembayaranPerBulan[$i] ?? 0) +
                                   ($countDomainPerBulan[$i] ?? 0) +
                                   ($countBtsPerBulan[$i] ?? 0);
        }

        return view('dashboard', compact(
            'countBelumTerbayar',
            'countBtsJatuhTempo',
            'countPembayaranBelumLunas',
            'countMotorPajakJatuhTempo',
            'activeReminders',
            'pendingReminders',
            'transactionCounts'
        ));
    }
}
