<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Models\JenisPembayaran;

class JenisPembayaranController extends Controller
{
    public function index(Request $request)
{
    $tanggalFilter = $request->input('tanggal_filter');
    $search = $request->input('search');

    $query = JenisPembayaran::query();

    if ($tanggalFilter) {
        $query->whereYear('tgl_jatuh_tempo', $tanggalFilter);
    }

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('jenis_pembayaran', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%');
        });
    }

    $pembayarans = $query->orderBy('created_at', 'desc')->paginate(15);

    // Mendapatkan pembayaran yang jatuh tempo dalam waktu kurang dari 1 minggu
    $pembayarans_expired = JenisPembayaran::where('tanggal_jatuh_tempo', '<=', Carbon::now()->addWeek())->get();

    return view('pembayaran_bulanan.tb_jenispembayaran', compact('pembayarans', 'pembayarans_expired', 'tanggalFilter'));
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

    $pembayaran = JenisPembayaran::create([
        'jenis_pembayaran' => $request->input('jenis_pembayaran'),
        'status' => $request->input('status'),
        'tanggal_jatuh_tempo' => Carbon::now()->addMonth(),  // Menambah 1 bulan dari sekarang
    ]);

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
        'tanggal_jatuh_tempo' => 'required|date', // Validate due date
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