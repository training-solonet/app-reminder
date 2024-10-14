<?php

namespace App\Imports;
use App\Models\Pajak;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PajakImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $indexKe = 1;

        foreach($collection as $row){
            if($indexKe > 1){

                $data['no_faktur']  = !empty($row[0]) ? $row[0] : '';
                $data['nama_user']  = !empty($row[1]) ? $row[1] : '';
                $data['total']      = !empty($row[2]) ? $row[2] : '';
                $data['dpp']        = !empty($row[3]) ? $row[3] : '';
                $data['ppn']        = !empty($row[4]) ? $row[4] : '';

                Pajak::create($data);
            }

            $indexKe++;
        }
    }
}
