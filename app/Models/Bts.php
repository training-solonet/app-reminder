<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bts extends Model
{
    use HasFactory;

    public function getJatuhTempoAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected $table = 'bts';

    protected $fillable = [
        'nama_bts',
        'nama_user',
        'telepon',
        'tahun_awal',
        'jatuh_tempo',
        'nominal_pertahun',
        'keterangan',
        'status'
    ];

    public function transaksiBts()
    {
        return $this->hasMany(TransaksiBts::class, 'bts_id');
    }
}

