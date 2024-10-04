<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\TransaksiDomain;
use Carbon\Carbon;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal_filter');
        $search = $request->input('search');

        $query = Domain::query();

        if ($tanggalFilter) {
            $query->whereYear('tgl_expired', $tanggalFilter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_domain', 'like', '%' . $search . '%')
                ->orWhere('status_berlangganan', 'like', '%' . $search . '%');
            });
        }

        $domains = $query->orderBy('created_at', 'desc')->paginate(2);

        $domains_expired = Domain::where('tgl_expired', '<=', Carbon::now()->addDays(30))->get();

        return view('domain_hosting.tb_domain', compact('domains', 'domains_expired', 'tanggalFilter'));
    }
    

    public function store(Request $request)
{
    $request->validate([
        'nama_domain' => 'required|unique:domain,nama_domain',
        'tgl_expired' => 'required|date',
        'nama_perusahaan' => 'required',
        'nominal' => 'required|numeric', 
        'status_berlangganan' => 'required|in:Aktif,Tidak Aktif',  
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
        'nominal' => 'required|numeric',
        'status_berlangganan' => 'required|in:Aktif,Tidak Aktif',  
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
