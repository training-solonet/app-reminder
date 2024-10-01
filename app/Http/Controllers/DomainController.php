<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\TransaksiDomain;
use Carbon\Carbon;

class DomainController extends Controller
{

    
    public function index()
    {
        $domains = Domain::all();
        $domains_expired = Domain::where('tgl_expired', '<=', Carbon::now()->addDays(30))
            ->get()
            ->filter(function ($domain) {
                $transaksi = TransaksiDomain::where('domain_id', $domain->id)->first();
                return !$transaksi || $transaksi->status === 'belum-lunas';
            });
    
        return view('domain_hosting.tb_domain', compact('domains', 'domains_expired'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'nama_domain' => 'required|unique:domain,nama_domain',
            'tgl_expired' => 'required|date',
            'nama_perusahaan' => 'required',
        ]);

        Domain::create($request->all());
        return redirect()->route('domain.index')->with('success', 'Domain berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_domain' => 'required|unique:domain,nama_domain,' . $id,
            'tgl_expired' => 'required|date',
            'nama_perusahaan' => 'required',
        ]);

        $domain = Domain::find($id);
        $domain->update($request->all());

        return redirect()->route('domain.index')->with('success', 'Domain berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $domain = Domain::find($id);
        $domain->delete();

        return redirect()->route('domain.index')->with('success', 'Domain berhasil dihapus.');
    }
}
