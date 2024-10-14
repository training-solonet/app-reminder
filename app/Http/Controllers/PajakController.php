<?php

namespace App\Http\Controllers;

use App\Imports\PajakImport;
use Illuminate\Http\Request;
use App\Models\Pajak;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PajakController extends Controller
{
    public function index(Request $request)
{
    // Ambil nilai dari input 'search'
    $search = $request->input('search');

    // Mulai query
    $pajak = Pajak::query();

    // Jika ada input 'search', tambahkan filter ke query
    if ($search) {
        $pajak = $pajak->where('no_faktur', 'like', '%' . $search . '%')
                       ->orWhere('nama_user', 'like', '%' . $search . '%');
    }

    // Paginate dengan hasil query, misalnya 100 data per halaman
    $pajak = $pajak->paginate(50);

    // Kirimkan hasil pencarian ke view
    return view('pajak.tb_pajak', compact('pajak'));
}


    public function cache(Request $request)
    {
        $data = Cache::remember('users', Carbon::now()->addMinutes(5), function() {
            return Pajak::get();
        });

        return view('pajak.tb_pajak', compact('data'));
    }

    public function import(Request $request)
    {
        return view('pajak');
    }

    public function import_proses(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xls,xlsx'
    ]);

    Pajak::truncate();

    Excel::import(new PajakImport(), $request->file('file'));

    return redirect()->back()->with('success', 'Data Berhasil Di Import');
}

}
