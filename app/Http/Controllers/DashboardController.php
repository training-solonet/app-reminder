<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Bts;
use App\Models\Domain;
use App\Models\Pembayaran;
use App\Models\Reminder;

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

        $pembayarans = Pembayaran::where('status_bayar', 'belum-lunas')->orderBy('created_at', 'asc')->get();

        $countPembayaranBelumLunas = $pembayarans->count();

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

        $countMotorPerBulan = Motor::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $countRemindersPerBulan = Reminder::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Gabungkan data menjadi satu array per bulan (1 - 12)
        $transactionCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $transactionCounts[] = [
                'bulan' => $i,
                'pembayaran' => $countPembayaranPerBulan[$i] ?? 0,                
                'domain' => $countDomainPerBulan[$i] ?? 0,
                'bts' => $countBtsPerBulan[$i] ?? 0,
                'motor' => $countMotorPerBulan[$i] ?? 0,
                'reminder' => $countRemindersPerBulan[$i] ?? 0
            ];
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
