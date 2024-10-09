<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use App\Models\Karyawan;
use App\Models\Motor;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_motor', 'like', "%{$search}%")
                  ->orWhere('jenis_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('motor', function ($q) use ($search) { 
                      $q->where('plat_nomor', 'like', "%{$search}%");
                   })
                  ->orWhereHas('karyawan', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->paginate(15);

        $karyawan = Karyawan::all();
        $motor = Motor::all();

        return view('inventory_motor.tb_transaksi', compact('transaksi', 'karyawan', 'motor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|string',
            'nama_motor' => 'required|string',
            'nota_pajak' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_karyawan' => 'required|exists:karyawan,id',
            'plat_nomor' => 'required|exists:motors,id',
            'nominal' => 'required|numeric',
        ]);

        $data = $request->only('jenis_transaksi', 'nama_motor', 'plat_nomor', 'id_karyawan', 'nominal');

        if ($request->hasFile('nota_pajak')) {
            $file = $request->file('nota_pajak');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/notas', $filename);
            $data['nota_pajak'] = $filename;
        }

        if ($request->jenis_transaksi == 'Bayar Pajak') {
            $motor = Motor::find($request->plat_nomor);
            $motor->tanggal_pajak = $motor->tanggal_pajak->addYear();
            $motor->save();
        }

        Transaksi::create($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }


    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'jenis_transaksi' => 'required|string',
            'nama_motor' => 'required|string',
            'nota_pajak' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_karyawan' => 'required|exists:karyawan,id',
            'plat_nomor' => 'required|exists:motors,id',
            'nominal' => 'required|numeric',
        ]);

        $data = $request->only('jenis_transaksi', 'nama_motor', 'plat_nomor', 'id_karyawan', 'nominal');

        if ($request->hasFile('nota_pajak')) {
            if ($transaksi->nota_pajak) {
                Storage::delete('public/notas/' . $transaksi->nota_pajak);
            }

            $file = $request->file('nota_pajak');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/notas', $filename);
            $data['nota_pajak'] = $filename;
        }

        if ($request->jenis_transaksi == 'Bayar Pajak' && $request->plat_nomor != $transaksi->plat_nomor) {
            $oldMotor = Motor::find($transaksi->plat_nomor);
            $oldMotor->tanggal_pajak = $oldMotor->tanggal_pajak->subYear(); 
            $oldMotor->save();

            $newMotor = Motor::find($request->plat_nomor);
            $newMotor->tanggal_pajak = $newMotor->tanggal_pajak->addYear();
            $newMotor->save();
        }

        $transaksi->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }


    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->jenis_transaksi == 'Bayar Pajak') {
            $motor = Motor::find($transaksi->plat_nomor);
            $motor->tanggal_pajak = $motor->tanggal_pajak->subYear();
            $motor->save();
        }

        if ($transaksi->nota_pajak) {
            Storage::delete('public/notas/' . $transaksi->nota_pajak);
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
