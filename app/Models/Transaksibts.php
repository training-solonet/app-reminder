<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksibts extends Model
{
    use HasFactory;

    public function getTglTransaksiAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected $table = 'transaksi_bts';

    protected $fillable = [
        'tgl_transaksi',
        'bts_id',
        'nominal',
        'bukti',
        'status',
    ];

    public function bts()
    {
        return $this->belongsTo(Bts::class, 'bts_id');
    }
}
