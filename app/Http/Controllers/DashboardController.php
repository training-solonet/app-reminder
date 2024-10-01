<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Bts;
use App\Models\Pembayaran;

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

        $pembayarans = Pembayaran::where('status_bayar', 'belum-lunas')
                                 ->orderBy('tgl_bayar', 'asc')
                                 ->get();
        $countPembayaranBelumLunas = $pembayarans->count();

        return view('dashboard', compact('countBelumTerbayar', 'countBtsJatuhTempo', 'countPembayaranBelumLunas'));
    }
}