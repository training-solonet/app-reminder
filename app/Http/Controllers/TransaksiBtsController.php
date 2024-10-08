<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiBts;
use App\Models\Bts;
use Illuminate\Support\Facades\Storage;

class TransaksiBtsController extends Controller
{
    public function index(Request $request)
{
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $search = $request->input('search');

    $query = TransaksiBts::query();

    if ($tanggalAwal && $tanggalAkhir) {
        $query->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
    }

    if ($search) {
        $query->whereHas('bts', function ($q) use ($search) {
            $q->where('nama_bts', 'like', "%{$search}%");
        })->orWhere('status', 'like', "%{$search}%");
    }

    $transaksi_bts = $query->orderBy('created_at', 'desc')->paginate(15);

    $bts = Bts::all();

    return view('bts_kontrak.tb_transaksi_bts', compact('transaksi_bts', 'bts', 'tanggalAwal', 'tanggalAkhir', 'search'));
}


    public function store(Request $request)
    {
        $request->validate([
            'bts_id' => 'required|exists:bts,id',
            'tgl_transaksi' => 'required|date',
            'nominal' => 'required|numeric',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:lunas,belum_lunas',
        ]);

        $data = $request->only('bts_id', 'tgl_transaksi', 'nominal', 'status');

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_transaksi', $filename);
            $data['bukti'] = $filename;
        }

        $bts = BTS::findOrFail($request->bts_id);
        if ($request->status === 'lunas') {
            if (!$bts->jatuh_tempo) {
                $bts->jatuh_tempo = now()->addDays(365);
            } else {
                $bts->jatuh_tempo = \Carbon\Carbon::parse($bts->jatuh_tempo)->addDays(365);
            }
            $bts->save();
        }

        TransaksiBts::create($data);

        return redirect()->route('transaksi_bts.index')->with('success', 'Transaksi BTS berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bts_id' => 'required|exists:bts,id',
            'tgl_transaksi' => 'required|date',
            'nominal' => 'required|numeric',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:lunas,belum_lunas',
        ]);

        $transaksi = TransaksiBts::findOrFail($id);
        $bts = BTS::findOrFail($request->bts_id);
        
        if ($transaksi->status === 'lunas' && $request->status === 'belum_lunas') {
            if ($bts->jatuh_tempo) {
                $bts->jatuh_tempo = \Carbon\Carbon::parse($bts->jatuh_tempo)->subDays(365);
            }
        }

        if ($transaksi->status === 'belum_lunas' && $request->status === 'lunas') {

            if (!$bts->jatuh_tempo) {
                $bts->jatuh_tempo = now()->addDays(365);
            } else {
                $bts->jatuh_tempo = \Carbon\Carbon::parse($bts->jatuh_tempo)->addDays(365);
            }
        }

        $bts->save();

        $transaksi->bts_id = $request->bts_id;
        $transaksi->tgl_transaksi = $request->tgl_transaksi;
        $transaksi->nominal = $request->nominal;
        $transaksi->status = $request->status;

        if ($request->hasFile('bukti')) {
            if ($transaksi->bukti) {
                Storage::delete('public/bukti_transaksi/' . $transaksi->bukti);
            }
            
            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_transaksi', $filename);
            $transaksi->bukti = $filename;
        }

        $transaksi->save();

        return redirect()->route('transaksi_bts.index')->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiBts::findOrFail($id);
        $bts = BTS::findOrFail($transaksi->bts_id);

        if ($bts->jatuh_tempo) {
            $bts->jatuh_tempo = \Carbon\Carbon::parse($bts->jatuh_tempo)->subDays(365);
            $bts->save(); 
        }

        if ($transaksi->bukti) {
            Storage::delete('public/bukti_transaksi/' . $transaksi->bukti);
        }

        $transaksi->delete();

        return redirect()->route('transaksi_bts.index')->with('success', 'Transaksi berhasil dihapus');
    }
}