<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoKamar extends Model
{
    protected $table = 'foto_kamar';

    protected $fillable = [
        'kamar_id',
        'foto_url',
        'foto_path',
        'foto_thumb_url',
        'foto_thumb_path',
        'mime_type',
        'ukuran_file',
        'lebar',
        'tinggi',
        'is_thumbnail',
        'sort_order',
        'caption',
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
    ];

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
}
