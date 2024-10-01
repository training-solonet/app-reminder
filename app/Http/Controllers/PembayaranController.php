<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran; 
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
{
    $defaultDate = now()->startOfMonth()->toDateString();
    $tanggalFilter = $request->input('tanggal_filter', null);
    $search = $request->input('search');
    $pembayarans = Pembayaran::when($tanggalFilter, function ($query, $tanggalFilter) {
        return $query->whereDate('tgl_bayar', $tanggalFilter);
    })
    ->when($search, function ($query, $search) {
        return $query->where('pengguna', 'like', "%{$search}%")
                     ->orWhere('no_telp', 'like', "%{$search}%");
    })
    ->paginate(2); 
    $jenispembayaran = JenisPembayaran::all();
    return view('pembayaran_bulanan.tb_pembayaran', compact('pembayarans', 'jenispembayaran', 'tanggalFilter', 'search'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna' => 'required',
            'no_telp' => 'required',
            'keterangan' => 'nullable',
            'id_jenis_pembayaran' => 'required',
            'tgl_bayar' => 'required|date',
            'status_bayar' => 'required|in:lunas,belum-lunas',
            'bulan_bayar' => 'required',
        ]);

        Pembayaran::create($validated);

        return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Ditambahkan');
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'pengguna' => 'required',
            'no_telp' => 'required',
            'keterangan' => 'nullable',
            'id_jenis_pembayaran' => 'required',
            'tgl_bayar' => 'required|date',
            'status_bayar' => 'required|in:lunas,belum-lunas',
            'bulan_bayar' => 'required',
        ]);

        $pembayaran->update($validated);

        return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Diupdate');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Dihapus');
    }
    
}
