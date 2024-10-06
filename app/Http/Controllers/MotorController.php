<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Karyawan;
use App\Models\Motor;

class MotorController extends Controller
{
    public function index(Request $request)
{
    $query = Motor::query();

    // Pencarian berdasarkan nama motor atau plat nomor
    if ($request->has('search') && $request->search != null) {
        $query->where('nama_motor', 'like', '%' . $request->search . '%')
              ->orWhere('plat_nomor', 'like', '%' . $request->search . '%');
    }

    // Filter berdasarkan tanggal mulai dan akhir
    if ($request->has('start_date') && $request->start_date != null) {
        $query->whereDate('tanggal_pajak', '>=', $request->start_date);
    }

    if ($request->has('end_date') && $request->end_date != null) {
        $query->whereDate('tanggal_pajak', '<=', $request->end_date);
    }

    // Paginate hasil
    $motor = $query->paginate(5);

    // Ambil semua data karyawan
    $karyawan = Karyawan::all();

    return view('inventory_motor.tb_motor', compact('motor', 'karyawan'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:255',
            'tanggal_pajak' => 'required|date',
            'foto_motor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_karyawan' => 'required|string|max:255',
            'tahun_motor' => 'required|integer', // Validasi baru untuk tahun motor
        ]);

        $data = $request->only('nama_motor', 'plat_nomor', 'tanggal_pajak', 'id_karyawan', 'tahun_motor');

        if ($request->hasFile('foto_motor')) {
            $file = $request->file('foto_motor');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/motors', $filename);
            $data['foto_motor'] = $filename;
        }

        Motor::create($data);

        return redirect()->route('motor.index')->with('success', 'Motor berhasil ditambahkan');
    }

    public function update(Request $request, Motor $motor)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:255',
            'tanggal_pajak' => 'required|date',
            'foto_motor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_karyawan' => 'required|string|max:255',
            'tahun_motor' => 'required|integer', // Validasi baru untuk tahun motor
        ]);

        $data = $request->only('nama_motor', 'plat_nomor', 'tanggal_pajak', 'id_karyawan', 'tahun_motor');

        if ($request->hasFile('foto_motor')) {
            if ($motor->foto_motor) {
                Storage::delete('public/motors/' . $motor->foto_motor);
            }

            $file = $request->file('foto_motor');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/motors', $filename);
            $data['foto_motor'] = $filename;
        }

        $motor->update($data);

        return redirect()->route('motor.index')->with('success', 'Motor telah diupdate.');
    }

    public function destroy(Motor $motor)
    {
        if ($motor->foto_motor) {
            Storage::delete('public/motors/' . $motor->foto_motor);
        }

        $motor->delete();

        return redirect()->route('motor.index')->with('success', 'Motor telah dihapus.');
    }
}
