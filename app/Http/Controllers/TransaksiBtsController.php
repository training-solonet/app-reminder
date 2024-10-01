<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiBts;
use App\Models\Bts;
use Illuminate\Support\Facades\Storage;

class TransaksiBtsController extends Controller
{
    public function index()
    {
        $transaksi_bts = TransaksiBts::paginate(5);
        $bts = Bts::all();

        return view('bts_kontrak.tb_transaksi_bts', compact('transaksi_bts', 'bts'));
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

        TransaksiBts::create($data);

        return redirect()->route('transaksi_bts.index')->with('success', 'Transaksi BTS berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiBts::findOrFail($id);
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
        
        if ($transaksi->bukti) {
            Storage::delete('public/bukti_transaksi/' . $transaksi->bukti);
        }
    
        $transaksi->delete();
    
        return redirect()->route('transaksi_bts.index')->with('success', 'Transaksi berhasil dihapus');
    }
}    


