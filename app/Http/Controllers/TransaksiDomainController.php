<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDomain;
use App\Models\Domain;
use Illuminate\Http\Request;

class TransaksiDomainController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiDomain::all();
        $domain = Domain::all();
        return view('domain_hosting.tb_transaksi_domain', compact('transaksis', 'domain'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_transaksi' => 'required|date',
            'domain_id' => 'required|exists:domain,id', 
            'nominal' => 'required|numeric',
            'status' => 'required|in:lunas,belum-lunas',
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->only('tgl_transaksi', 'domain_id', 'nominal', 'status'); 

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/buktidomain', $filename);
            $data['bukti'] = $filename;
        }

        TransaksiDomain::create($data);

        return redirect()->route('transaksi_domain.index')->with('success', 'Transaksi Domain berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_transaksi' => 'required|date',
            'domain_id' => 'required|exists:domain,id', 
            'nominal' => 'required|numeric',
            'status' => 'required|in:lunas,belum-lunas',
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $transaksi = TransaksiDomain::findOrFail($id);
        $data = $request->only('tgl_transaksi', 'domain_id', 'nominal', 'status'); 

        if ($request->hasFile('bukti')) {
            // Hapus file bukti lama jika ada
            if ($transaksi->bukti && file_exists(storage_path('app/public/buktidomain/' . $transaksi->bukti))) {
                unlink(storage_path('app/public/buktidomain/' . $transaksi->bukti));
            }

            $file = $request->file('bukti');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/buktidomain', $filename);
            $data['bukti'] = $filename;
        }

        $transaksi->update($data);

        return redirect()->route('transaksi_domain.index')->with('success', 'Transaksi Domain berhasil diupdate');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiDomain::findOrFail($id);

        if ($transaksi->bukti && file_exists(storage_path('app/public/buktidomain/' . $transaksi->bukti))) {
            unlink(storage_path('app/public/buktidomain/' . $transaksi->bukti));
        }

        $transaksi->delete();

        return redirect()->route('transaksi_domain.index')->with('success', 'Transaksi Domain berhasil dihapus');
    }
}
