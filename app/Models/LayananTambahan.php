<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananTambahan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'tipe_siklus',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(RequestLayanan::class);
    }
}
