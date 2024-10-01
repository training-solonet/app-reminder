<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use App\Models\Karyawan;
use App\Models\Motor;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::paginate(5);
        $karyawan = Karyawan::all();  
        $motor = Motor::all();      
        
        return view('inventory_motor.tb_transaksi', compact('transaksi', 'karyawan', 'motor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|string',
            'plat_nomor' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'nota_pajak' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'id_karyawan' => 'required|exists:karyawan,id', 
            'id_motor' => 'required|exists:motors,id',       
            'nominal' => 'required|numeric',
        ]);

        $data = $request->only('jenis_transaksi', 'plat_nomor', 'tanggal_transaksi', 'id_karyawan','id_motor', 'nominal');

        if ($request->hasFile('nota_pajak')) {
            $file = $request->file('nota_pajak');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/notas', $filename);
            $data['nota_pajak'] = $filename;
        }

        Transaksi::create($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'jenis_transaksi' => 'required|string|in:Pinjam,Bayar Pajak,Servis',
            'plat_nomor' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'nota_pajak' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'id_karyawan' => 'required|exists:karyawan,id', 
            'id_motor' => 'required|exists:motors,id',
            'nominal' => 'required|numeric',
        ]);

        $data = $request->only('jenis_transaksi', 'plat_nomor', 'tanggal_transaksi', 'id_karyawan','id_motor', 'nominal');

        if ($request->hasFile('nota_pajak')) {
            if ($transaksi->nota_pajak) {
                Storage::delete('public/notas/' . $transaksi->nota_pajak);
            }

            $file = $request->file('nota_pajak');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/notas', $filename);
            $data['nota_pajak'] = $filename;
        }

        $transaksi->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {

        if ($transaksi->nota_pajak) {
            Storage::delete('public/notas/' . $transaksi->nota_pajak);
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
