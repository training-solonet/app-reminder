<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KaryawanExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting
{
    public function collection()
    {
        return Karyawan::select('nama', 'nik', 'jenis_kelamin', 'tgl_masuk', 'tgl_lahir', 'tempat_lahir', 'no_hp', 'agama', 'divisi', 'jabatan', 'alamat', 'status_karyawan', 'size_baju')
                        ->get();
    }

    public function map($item): array
    {
        setlocale(LC_TIME, 'id_ID.UTF-8');

        return [
            $item->nama,
            $item->nik,
            $item->jenis_kelamin,
            strftime('%A, %d %B %Y', strtotime($item->tgl_masuk)),
            strftime('%A, %d %B %Y', strtotime($item->tgl_lahir)),
            $item->tempat_lahir,
            $item->no_hp,
            $item->agama,
            $item->divisi,
            $item->jabatan,
            $item->alamat,
            $item->status_karyawan,
            $item->size_baju
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Jenis Kelamin',
            'Tanggal Masuk',
            'Tanggal Lahir',
            'Tempat Lahir',
            'No HP',
            'Agama',
            'Divisi',
            'Jabatan',
            'Alamat',
            'Status Karyawan',
            'Size Baju'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
