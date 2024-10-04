<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran; 
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
{
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $idJenisPembayaran = $request->input('id_jenis_pembayaran');
    $search = $request->input('search');
    
    $pembayarans = Pembayaran::when($tanggalAwal && $tanggalAkhir, function ($query) use ($tanggalAwal, $tanggalAkhir) {
        return $query->whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir]);
    })
    ->when($idJenisPembayaran, function ($query) use ($idJenisPembayaran) {
        return $query->where('id_jenis_pembayaran', $idJenisPembayaran);
    })
    ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('pengguna', 'like', "%{$search}%")
              ->orWhere('no_telp', 'like', "%{$search}%");
        });
    })
    ->paginate(10);
    
    $jenispembayaran = JenisPembayaran::all();
    return view('pembayaran_bulanan.tb_pembayaran', compact('pembayarans', 'jenispembayaran', 'tanggalAwal', 'tanggalAkhir', 'idJenisPembayaran', 'search'));
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
