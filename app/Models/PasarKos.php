<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasarKos extends Model
{
    protected $table = 'pasar_kos';

    protected $fillable = [
        'user_id',
        'nama_barang',
        'deskripsi',
        'harga',
        'foto_url',
        'kontak',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
