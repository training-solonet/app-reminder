<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    protected $dates = ['created_at'];

    public function getTanggalTransaksiAttribute($value)
    {
        return Carbon::parse($value);
    }
    protected $table = 'transaksi'; 
    
    protected $fillable = [
        'jenis_transaksi',
        'nama_motor',
        'plat_nomor',
        'nota_pajak',
        'id_karyawan',
        'nominal',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'plat_nomor');
    }
}   
