<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'tb_jenis_pembayaran';
    
    protected $fillable = [
        'jenis_pembayaran',
        'status',
        'tanggal_jatuh_tempo', // New field
    ];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_jenis_pembayaran');
    }
}
