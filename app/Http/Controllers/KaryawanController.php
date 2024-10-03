<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index(Request $request) 
{
    $query = Karyawan::query();

    // Filter berdasarkan input pencarian
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('nik', 'like', "%$search%")
              ->orWhere('jenis_kelamin', 'like', "%$search%")
              ->orWhere('tgl_masuk', 'like', "%$search%")
              ->orWhere('tgl_lahir', 'like', "%$search%")
              ->orWhere('no_hp', 'like', "%$search%")
              ->orWhere('divisi', 'like', "%$search%")
              ->orWhere('jabatan', 'like', "%$search%")
              ->orWhere('status_karyawan', 'like', "%$search%");
        });
    }

    // Pagination
    $karyawan = $query->paginate(1);

    return view('inventory_motor.tb_karyawan', compact('karyawan'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required|date',
        ]);

        Karyawan::create($request->all());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required|date',
        ]);

        $karyawan = Karyawan::find($id);
        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}