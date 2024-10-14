<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pajak extends Model
{
    use HasFactory;


    protected $table = 'pajak';

    protected $fillable = [
        'no_faktur',
        'nama_user',
        'total',
        'dpp',
        'ppn'
    ];

}

