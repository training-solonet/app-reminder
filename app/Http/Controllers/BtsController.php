<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bts;
use Carbon\Carbon;

class BtsController extends Controller
{
    public function index(Request $request)
{
    $tanggalFilter = $request->input('tanggal_filter');
    $search = $request->input('search');

    $query = Bts::query();

    if ($tanggalFilter) {
        $query->whereYear('jatuh_tempo', $tanggalFilter);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama_bts', 'like', "%{$search}%")
              ->orWhere('nama_user', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

    $bts = $query->orderBy('created_at', 'desc')->paginate(15);

    $bts_expired = Bts::whereDate('jatuh_tempo', '<=', Carbon::now()->addDays(30))->get();

    return view('bts_kontrak.tb_bts', compact('bts', 'bts_expired', 'tanggalFilter', 'search'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_bts' => 'required|string|max:255',
            'nama_user' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'tahun_awal' => 'required|integer',
            'jatuh_tempo' => 'required|date',
            'nominal_pertahun' => 'required|numeric',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string',
        ]);

        Bts::create($request->all());

        return redirect()->route('bts.index')->with('success', 'BTS berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bts' => 'required|string|max:255',
            'nama_user' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'tahun_awal' => 'required|integer',
            'jatuh_tempo' => 'required|date',
            'nominal_pertahun' => 'required|numeric',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string',
        ]);

        $bts = Bts::find($id);
        $bts->update($request->all());

        return redirect()->route('bts.index')->with('success', 'BTS berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bts = Bts::find($id);
        $bts->delete();

        return redirect()->route('bts.index')->with('success', 'BTS berhasil dihapus.');
    }
}

