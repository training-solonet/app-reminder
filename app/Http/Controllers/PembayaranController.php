<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    ->get();
    
    $jenispembayaran = JenisPembayaran::all();
    return view('pembayaran_bulanan.tb_pembayaran', compact('pembayarans', 'jenispembayaran', 'tanggalAwal', 'tanggalAkhir', 'idJenisPembayaran', 'search'));
}

    public function store(Request $request)
    {
        $request->validate([
            'pengguna' => 'required',
            'no_telp' => 'required',
            'keterangan' => 'nullable',
            'id_jenis_pembayaran' => 'required',
            'status_bayar' => 'required|in:lunas,belum-lunas',
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('pengguna', 'no_telp', 'keterangan', 'id_jenis_pembayaran', 'status_bayar');

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/buktipembayaran', $filename);
            $data['bukti'] = $filename;
        }

        Pembayaran::create($data);

        $jumlahTransaksi = Pembayaran::where('id_jenis_pembayaran', $request->id_jenis_pembayaran)->count();

        $jenisPembayarans = JenisPembayaran::find($request->id_jenis_pembayaran);
        if ($jenisPembayarans) {
            $jenisPembayarans->tanggal_jatuh_tempo = Carbon::now()->addMonths($jumlahTransaksi);
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
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/buktipembayaran', $filename);
            $validated['bukti'] = $filename;
        }

        if ($pembayaran->id_jenis_pembayaran != $request->id_jenis_pembayaran) {
            $jenisPembayaranLama = JenisPembayaran::find($pembayaran->id_jenis_pembayaran);
            if ($jenisPembayaranLama) {
                $jumlahTransaksiLama = Pembayaran::where('id_jenis_pembayaran', $pembayaran->id_jenis_pembayaran)->count();
                $jenisPembayaranLama->tanggal_jatuh_tempo = Carbon::now()->addMonths($jumlahTransaksiLama - 1); // Kurangi satu bulan
                $jenisPembayaranLama->save();
            }

            $jenisPembayaranBaru = JenisPembayaran::find($request->id_jenis_pembayaran);
            if ($jenisPembayaranBaru) {
                $jumlahTransaksiBaru = Pembayaran::where('id_jenis_pembayaran', $request->id_jenis_pembayaran)->count();
                $jenisPembayaranBaru->tanggal_jatuh_tempo = Carbon::now()->addMonths($jumlahTransaksiBaru);
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
            $jumlahTransaksi = Pembayaran::where('id_jenis_pembayaran', $pembayaran->id_jenis_pembayaran)->count();

            if ($jumlahTransaksi > 1) {
                $jenisPembayaran->tanggal_jatuh_tempo = Carbon::parse($jenisPembayaran->tanggal_jatuh_tempo)->subMonth();
            } else {
                $jenisPembayaran->tanggal_jatuh_tempo = Carbon::now();
            }
            
            $jenisPembayaran->save();
        }

        if ($pembayaran->bukti) {
            Storage::delete('public/buktipembayaran/' . $pembayaran->bukti);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Data Pembayaran Berhasil Dihapus dan Jatuh Tempo Diperbarui');
    }
}