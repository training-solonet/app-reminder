<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Motor extends Model
{
    use HasFactory;

     public function getTanggalPajakAttribute($value)
     {
         return Carbon::parse($value);
     }

    protected $table = 'motors';

    protected $fillable = [
        'nama_motor',
        'plat_nomor',
        'tanggal_pajak',
        'foto_motor',
        'id_karyawan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

}
