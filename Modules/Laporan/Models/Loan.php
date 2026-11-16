<?php

namespace Modules\Laporan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = ['id', 'user_id', 'titik_drainase_id', 'tinggi_air_cm', 'status_kondisi', 'deskripsi', 'foto_bukti'];
    protected $casts = ['borrowed_at' => 'date', 'due_at' => 'date', 'returned_at' => 'date'];

    // Peminjaman selalu terhubung ke satu buku dan satu pengguna.
    public function book(): BelongsTo { return $this->belongsTo(Book::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
