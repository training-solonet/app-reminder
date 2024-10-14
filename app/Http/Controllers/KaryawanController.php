<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request) 
{
    $query = Karyawan::query();

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

    $karyawan = $query->orderBy('created_at', 'desc')->paginate(15);

    return view('inventory_motor.tb_karyawan', compact('karyawan'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|numeric',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required|date',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'no_hp' => 'required|numeric',
            'agama' => 'required',
            'divisi' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'status_karyawan' => 'required',
            'status_cuti' => 'nullable|boolean',
            'foto_karyawan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('nama', 'nik', 'jenis_kelamin', 'tgl_masuk','tgl_lahir','tempat_lahir','no_hp','agama','divisi','jabatan','alamat','status_karyawan','status_cuti');

        if ($request->hasFile('foto_karyawan')) {
            $file = $request->file('foto_karyawan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan', $filename);
            $data['foto_karyawan'] = $filename;
        }

        Karyawan::create($data);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|numeric',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required|date',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'no_hp' => 'required|numeric',
            'agama' => 'required',
            'divisi' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'status_karyawan' => 'required',
            'status_cuti' => 'nullable|boolean',
            'foto_karyawan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $karyawan = Karyawan::find($id);
        $data = $request->only('nama', 'nik', 'jenis_kelamin', 'tgl_masuk','tgl_lahir','tempat_lahir','no_hp','agama','divisi','jabatan','alamat','status_karyawan','status_cuti');

        if ($request->hasFile('foto_karyawan')) {
            if ($karyawan->foto_karyawan) {
                Storage::delete('public/karyawan/' . $karyawan->foto_karyawan);
            }
            
            $file = $request->file('foto_karyawan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan', $filename);
            $data['foto_karyawan'] = $filename;
        }

        $karyawan->update($data);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }


    public function destroy($id)
{
    $karyawan = Karyawan::findOrFail($id);

    if ($karyawan->foto_karyawan) {
        Storage::delete('public/karyawan/' . $karyawan->foto_karyawan);
    }

    $karyawan->delete();

    return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
}

}