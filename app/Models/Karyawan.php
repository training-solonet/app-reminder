<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory;

    protected $dates = ['tgl_masuk', 'tgl_lahir'];

    public function getTglMasukAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getTglLahirAttribute($value)
    {
        return Carbon::parse($value);
    }


    protected $table = 'karyawan'; 

    protected $fillable = [
        'nama',
        'nik',
        'jenis_kelamin',
        'tgl_masuk',
        'tgl_lahir',
        'tempat_lahir',
        'no_hp',
        'agama',
        'divisi',
        'jabatan',
        'alamat',
        'status_cuti',
        'status_karyawan',
        'foto_karyawan',
    ];

    public function motors()
    {
        return $this->hasMany(Motor::class, 'id_karyawan');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_karyawan');
    }
}


