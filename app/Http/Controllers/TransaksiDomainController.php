<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDomain;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiDomainController extends Controller
{
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal_filter');
        $search = $request->input('search');

        $queryDomain = Domain::query();

        $queryTransaksi = TransaksiDomain::query();

        if ($tanggalFilter) {
            $queryTransaksi->whereDate('tgl_transaksi', $tanggalFilter);
        }
        
        if ($search) {
            $queryTransaksi->whereHas('domain', function ($query) use ($search) {
                $query->where('nama_domain', 'like', "%{$search}%");
            })->orWhere('status', 'like', "%{$search}%");
        }

        $domain = $queryDomain->orderBy('created_at', 'desc')->get();
        $transaksis = $queryTransaksi->orderBy('created_at', 'desc')->get();

        return view('domain_hosting.tb_transaksi_domain', compact('transaksis', 'domain', 'tanggalFilter', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_transaksi' => 'required|date',
            'domain_id' => 'required|exists:domain,id', 
            'nominal' => 'required|numeric',
            'masa_perpanjang' => 'required|integer|min:1',
            'status' => 'required|in:lunas,belum-lunas',
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->only('tgl_transaksi', 'domain_id', 'nominal', 'masa_perpanjang', 'status');

        $domain = Domain::findOrFail($request->domain_id);
        if ($request->status === 'lunas') {
            $tahunDitambah = $request->masa_perpanjang * 365;
            if (!$domain->tgl_expired) {
                $domain->tgl_expired = now()->addDays($tahunDitambah);
            } else {
                $domain->tgl_expired = \Carbon\Carbon::parse($domain->tgl_expired)->addDays($tahunDitambah);
            }
            $domain->save();
        }

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
            'masa_perpanjang' => 'required|integer|min:1', 
            'status' => 'required|in:lunas,belum-lunas',
            'bukti' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        $transaksi = TransaksiDomain::findOrFail($id);
        $domain = Domain::findOrFail($request->domain_id);
        $masaPerpanjangLama = $transaksi->masa_perpanjang * 365;
    
        if ($transaksi->status === 'lunas' && $request->status === 'belum-lunas') {
            if ($domain->tgl_expired) {
                $domain->tgl_expired = \Carbon\Carbon::parse($domain->tgl_expired)->subDays($masaPerpanjangLama);
            }
        }
    
        if ($transaksi->status === 'belum-lunas' && $request->status === 'lunas') {
            $masaPerpanjangBaru = $request->masa_perpanjang * 365;
    
            if (!$domain->tgl_expired) {
                $domain->tgl_expired = now()->addDays($masaPerpanjangBaru);
            } else {
                $domain->tgl_expired = \Carbon\Carbon::parse($domain->tgl_expired)->addDays($masaPerpanjangBaru);
            }
        }

        $domain->save();
    
        if ($request->hasFile('bukti')) {
            if ($transaksi->bukti) {
                Storage::delete('public/buktidomain/' . $transaksi->bukti);
            }
            $file = $request->file('bukti');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/buktidomain', $filename);
            $data['bukti'] = $filename;
        }
        
        
        $transaksi->update($data);
        
    
        $data = $request->only('tgl_transaksi', 'domain_id', 'nominal', 'masa_perpanjang', 'status'); 
        $transaksi->update($data);
    
        return redirect()->route('transaksi_domain.index')->with('success', 'Transaksi Domain berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $transaksi = TransaksiDomain::findOrFail($id);

        $domain = Domain::findOrFail($transaksi->domain_id);

        $masaPerpanjang = $transaksi->masa_perpanjang * 365; 
        if ($domain->tgl_expired) {
            $domain->tgl_expired = \Carbon\Carbon::parse($domain->tgl_expired)->subDays($masaPerpanjang);
            $domain->save(); 
        }

        if ($transaksi->bukti && file_exists(storage_path('app/public/buktidomain/' . $transaksi->bukti))) {
            unlink(storage_path('app/public/buktidomain/' . $transaksi->bukti));
        }

        $transaksi->delete();

        return redirect()->route('transaksi_domain.index')->with('success', 'Transaksi Domain berhasil dihapus');
    }
}
