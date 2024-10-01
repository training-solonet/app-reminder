<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class JenisPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = JenisPembayaran::all();
        $pembayaran = Pembayaran::all();
        return view('pembayaran_bulanan.tb_jenispembayaran', compact('pembayarans','pembayaran'));
    }

    public function create()
    {
        return view('jenis_pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pembayaran' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak-aktif',
        ]);

        JenisPembayaran::create($request->all());

        return redirect()->route('jenis_pembayaran.index')
                         ->with('success', 'Jenis Pembayaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pembayaran = JenisPembayaran::find($id);
        return view('jenis_pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pembayaran' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak-aktif',
        ]);

        $pembayaran = JenisPembayaran::find($id);
        $pembayaran->update($request->all());

        return redirect()->route('jenis_pembayaran.index')
                         ->with('success', 'Jenis Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pembayaran = JenisPembayaran::find($id);
        $pembayaran->delete();

        return redirect()->route('jenis_pembayaran.index')
                         ->with('success', 'Jenis Pembayaran berhasil dihapus');
    }
}