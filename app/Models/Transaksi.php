<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    protected $dates = ['tanggal_transaksi'];

    public function getTanggalTransaksiAttribute($value)
    {
        return Carbon::parse($value);
    }
    protected $table = 'transaksi'; 
    
    protected $fillable = [
        'jenis_transaksi',
        'plat_nomor',
        'tanggal_transaksi',
        'nota_pajak',
        'id_karyawan',
        'id_motor',
        'nominal',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'id_motor');
    }
}   
