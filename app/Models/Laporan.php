<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'user_id',
        'titik_drainase_id',
        'tinggi_air_cm',
        'status_kondisi',
        'deskripsi',
        'foto',
    ];
}