<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\StatusKeluhan;
use App\Enums\PrioritasKeluhan;

class Keluhan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'kamar_id',
        'judul',
        'deskripsi',
        'status',
        'prioritas',
        'foto_url',
        'tanggapan_admin',
        'resolved_at',
    ];

    protected $casts = [
        'status' => StatusKeluhan::class,
        'prioritas' => PrioritasKeluhan::class,
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }

    public function komentars(): HasMany
    {
        return $this->hasMany(KeluhanKomentar::class)->oldest();
    }
}
