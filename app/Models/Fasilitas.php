<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';

    protected $fillable = [
        'nama',
        'slug',
        'icon',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kamar(): BelongsToMany
    {
        return $this->belongsToMany(Kamar::class, 'kamar_fasilitas');
    }
}
