<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagihanItem extends Model
{
    protected $table = 'tagihan_items';

    protected $fillable = [
        'tagihan_id',
        'nama_item',
        'nominal',
    ];

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}
