<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    public function getTglBayarAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected $table = 'tb_pembayaran_bulanan';

    protected $fillable = [
        'pengguna',
        'no_telp',
        'keterangan',
        'id_jenis_pembayaran',
        'tgl_bayar',
        'status_bayar',
        'bulan_bayar'
    ];

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran');
    }

}