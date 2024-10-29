<?php

namespace App\Http\Controllers;

use App\Imports\PajakImport;
use Illuminate\Http\Request;
use App\Models\Pajak;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FakturPajakExport;
use Illuminate\Support\Testing\Fakes\Fake;

class PajakController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pajak = Pajak::query();

        if ($search) {
            $pajak = $pajak->where('no_faktur', 'like', '%' . $search . '%')
                ->orWhere('nama_user', 'like', '%' . $search . '%');
        }

        $pajak = $pajak->get();

        return view('pajak.tb_pajak', compact('pajak'));
    }


    public function cache(Request $request)
    {
        $data = Pajak::get();

        return view('pajak.tb_pajak', compact('data'));
    }

    public function import(Request $request)
    {
        return view('pajak');
    }

    public function export_excel()
    {
        $filePath = resource_path('excel/templatexcel.xlsx');
        return response()->download($filePath);
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

    public function create(Request $request)
    {
        $tanggalFaktur = $request->get('tanggal_faktur');
        $export = new FakturPajakExport($tanggalFaktur);
        return Excel::download($export, 'faktur_pajak.xlsx');
    }
}
