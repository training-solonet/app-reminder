<?php

namespace App\Exports;

use App\Models\Pajak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class FakturPajakExport implements FromCollection, WithHeadings
{
    use Exportable;
    private $tanggalFaktur;

    public function __construct($tanggalFaktur)
    {
        $this->tanggalFaktur = $tanggalFaktur;
    }

    public function headings(): array
    {
        return [
            ['FK', 'KD_JENIS_TRANSAKSI', 'FG_PENGGANTI', 'NOMOR_FAKTUR', 'MASA_PAJAK', 'TAHUN_PAJAK', 'TANGGAL_FAKTUR', 'NPWP', 'NAMA', 'ALAMAT_LENGKAP', 'JUMLAH_DPP', 'JUMLAH_PPN', 'JUMLAH_PPNBM', 'ID_KETERANGAN_TAMBAHAN', 'FG_UANG_MUKA', 'UANG_MUKA_DPP', 'UANG_MUKA_PPN', 'UANG_MUKA_PPNBM', 'REFERENSI', 'KODE_DOKUMEN_PENDUKUNG'],
            ['LT', 'NPWP', 'NAMA', 'JALAN', 'BLOK', 'NOMOR', 'RT', 'RW', 'KECAMATAN', 'KELURAHAN', 'KABUPATEN', 'PROPINSI', 'KODE_POS', 'NOMOR_TELEPON'],
            ['OF', 'KODE_OBJEK', 'NAMA', 'HARGA_SATUAN', 'JUMLAH_BARANG', 'HARGA_TOTAL', 'DISKON', 'DPP', 'PPN', 'TARIF_PPNBM', 'PPNBM']
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Pajak::all();

        foreach ($user as $item) {
            $array[] = [
                [
                    'fk'                    => 'FK',
                    'kd_jenis_transaksi'    => '01',
                    'fg_pengganti'          => '0',
                    'no_faktur'             => substr($item->no_faktur, 2),
                    'masa_pajak'            => date('m', strtotime($this->tanggalFaktur)),
                    'tahun_pajak'           => date('Y', strtotime($this->tanggalFaktur)),
                    'tanggal_faktur'        => $this->tanggalFaktur,
                    'npwp'                  => '0000000000000000',
                    'nama'                  => '#PASPOR#NAMA#' . $item->nama_user,
                    'alamat_lengkap'        => 'SOLO',
                    'jumlah_dpp'            => sprintf("%.1f", $item->dpp),
                    'jumlah_ppn'            => sprintf("%.1f", $item->ppn),
                    'jumlah_ppnbm'          => '0',
                    'id_keterangan_tambahan' => null,
                    'fg_uang_muka'          => '0',
                    'uang_muka_dpp'         => '0',
                    'uang_muka_ppn'         => '0',
                    'uang_muka_ppnbm'       => '0',
                    'referensi'             => null,
                    'kode_dokumen_pendukung' => null,
                ],
                [
                    'lt'                    => 'FAPR',
                    'npwp_pt'               => 'PT SOLO JALA BUANA',
                    'nama_pt'               => 'JL. ARIFIN NO 129, KOTA SURAKARTA',
                    'jalan'                 => null,
                    'blok'                  => null,
                    'nomer'                 => null,
                    'rt'                    => null,
                    'rw'                    => null,
                    'kecamatan'             => null,
                    'kelurahan'             => null,
                    'kabupaten'             => null,
                    'propinsi'              => null,
                    'kode_pos'              => null,
                    'nomer_telepon'         => null,
                ],
                [
                    'of'                    => 'OF',
                    'kode_objek'            => 'Z688',
                    'nama_objek'            => 'BANDWIDTH',
                    'harga_satuan'          => sprintf("%.1f", $item->total),
                    'jumlah_barang'         => 1.0,
                    'harga_total'           => sprintf("%.1f", $item->total * 1),
                    'diskon'                => '0.0',
                    'dpp'                   => sprintf("%.1f", $item->dpp),
                    'ppn'                   => sprintf("%.1f", $item->ppn),
                    'tarif_ppnbm'           => '0.0',
                    'ppnbm'                 => '0.0',
                ]
            ];
        }
        return collect($array);
    }
}
