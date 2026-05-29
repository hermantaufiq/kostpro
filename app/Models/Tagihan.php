<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\StatusTagihan;

class Tagihan extends Model
{
    use SoftDeletes;

    protected $table = 'tagihan';

    protected $fillable = [
        'penyewaan_id',
        'user_id',
        'kode_tagihan',
        'periode_bulan',
        'periode_tahun',
        'jumlah_tagihan',
        'jumlah_denda',
        'total_tagihan',
        'status',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'reminder_count',
        'last_reminder_at',
        'catatan',
        'is_auto_generated',
    ];

    protected $casts = [
        'tanggal_tagihan' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
        'last_reminder_at' => 'datetime',
        'status' => StatusTagihan::class,
        'is_auto_generated' => 'boolean',
    ];

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }
}
