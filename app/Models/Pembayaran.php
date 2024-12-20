<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected $table = 'tb_pembayaran_bulanan';

    protected $fillable = [
        'pengguna',
        'no_telp',
        'keterangan',
        'id_jenis_pembayaran',
        'status_bayar',
        'bukti'
    ];

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran');
    }

}