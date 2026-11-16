<?php

namespace Modules\Laporan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    // Satu kategori menaungi banyak buku (one-to-many).
    public function books(): HasMany { return $this->hasMany(Book::class); }
}
