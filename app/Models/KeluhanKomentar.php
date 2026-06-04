<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeluhanKomentar extends Model
{
    protected $table = 'keluhan_komentars';

    protected $fillable = [
        'keluhan_id',
        'user_id',
        'isi',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function keluhan(): BelongsTo
    {
        return $this->belongsTo(Keluhan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
