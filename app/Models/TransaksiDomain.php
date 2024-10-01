<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransaksiDomain extends Model
{
    use HasFactory;

    public function getTglTransaksiAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected $table = 'transaksi_domain';

    protected $fillable = [
        'tgl_transaksi',
        'domain_id', 
        'nominal', 
        'status', 
        'bukti'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
