<?php

namespace Modules\TitikDrainase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Laporan\Models\Laporan;

class TitikDrainase extends Model
{
    // Nama tabel database yang dikelola model ini
    protected $table = 'titik_drainases';

    // Kolom-kolom yang dapat diisi secara massal (Mass Assignment Protection)
    protected $fillable = [
        'nama_titik',
        'kecamatan',
        'deskripsi',
        'kedalaman_cm',
    ];

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'id');
    }

    public function getNamaTitikAttribute()
    {
        return $this->attributes['nama_titik'] ?? null;
    }

    public function getKecamatanAttribute()
    {
        return $this->attributes['kecamatan'] ?? null;
    }

    public function getDeskripsiAttribute()
    {
        return $this->attributes['deskripsi'] ?? null;
    }

    public function getKedalamanAttribute()
    {
        return $this->attributes['kedalaman_cm'] ?? null;
    }
}
