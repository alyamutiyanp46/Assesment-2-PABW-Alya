<?php

namespace Modules\Laporan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    protected $fillable = ['id', 'user_id', 'titik_drainase_id', 'tinggi_air_cm', 'status_kondisi', 'deskripsi', 'foto_bukti'];

    // Buku menyimpan foreign key category_id dan mempunyai banyak transaksi.
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function loans(): HasMany { return $this->hasMany(Loan::class); }
}
