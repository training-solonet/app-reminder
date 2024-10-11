<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
{
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $idJenisPembayaran = $request->input('id_jenis_pembayaran');
    $search = $request->input('search');
    
    $pembayarans = Pembayaran::when($tanggalAwal && $tanggalAkhir, function ($query) use ($tanggalAwal, $tanggalAkhir) {
        return $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
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
            'status_bayar' => 'required|in:lunas,belum-lunas',
        ]);

        Pembayaran::create($validated);

        $jenisPembayarans = JenisPembayaran::find($request->id_jenis_pembayaran);
        if ($jenisPembayarans) {
            $jenisPembayarans->tanggal_jatuh_tempo = Carbon::now()->addMonth();
            $jenisPembayarans->save();
        }

        return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Ditambahkan dan Jatuh Tempo Diperbarui');
}

public function update(Request $request, Pembayaran $pembayaran)
{
    $validated = $request->validate([
        'pengguna' => 'required',
        'no_telp' => 'required',
        'keterangan' => 'nullable',
        'id_jenis_pembayaran' => 'required',
        'status_bayar' => 'required|in:lunas,belum-lunas',
    ]);

    if ($pembayaran->id_jenis_pembayaran != $request->id_jenis_pembayaran) {
        $jenisPembayaranLama = JenisPembayaran::find($pembayaran->id_jenis_pembayaran);
        if ($jenisPembayaranLama) {
            $jenisPembayaranLama->tgl_jatuh_tempo = Carbon::parse($jenisPembayaranLama->tgl_jatuh_tempo)->subMonth();
            $jenisPembayaranLama->save();
        }

        $jenisPembayaranBaru = JenisPembayaran::find($request->id_jenis_pembayaran);
        if ($jenisPembayaranBaru) {
            $jenisPembayaranBaru->tgl_jatuh_tempo = Carbon::now()->addMonth();
            $jenisPembayaranBaru->save();
        }
    }

    $pembayaran->update($validated);

    return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Diupdate dan Jatuh Tempo Diperbarui');
}


public function destroy(Pembayaran $pembayaran)
{
    $jenisPembayaran = JenisPembayaran::find($pembayaran->id_jenis_pembayaran);
    if ($jenisPembayaran) {
        $jenisPembayaran->tanggal_jatuh_tempo = Carbon::parse($jenisPembayaran->tgl_jatuh_tempo)->subMonth();
        $jenisPembayaran->save();
    }

    $pembayaran->delete();

    return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Dihapus dan Jatuh Tempo Diperbarui');
}

    
}
