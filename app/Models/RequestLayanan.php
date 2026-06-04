<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestLayanan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'layanan_tambahan_id',
        'penyewaan_id',
        'status',
        'catatan_user',
        'catatan_admin',
        'tagihan_item_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layananTambahan(): BelongsTo
    {
        return $this->belongsTo(LayananTambahan::class);
    }

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function tagihanItem(): BelongsTo
    {
        return $this->belongsTo(TagihanItem::class);
    }
}
