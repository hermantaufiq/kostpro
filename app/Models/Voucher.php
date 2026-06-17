<?php

namespace App\Models;

use App\Enums\StatusVoucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    protected $fillable = [
        'user_id',
        'kode_voucher',
        'nominal_diskon',
        'status',
        'sumber',
        'pembayaran_sumber_id',
        'tagihan_id',
        'pembayaran_id',
        'berlaku_sampai',
        'digunakan_pada',
    ];

    protected $casts = [
        'status' => StatusVoucher::class,
        'berlaku_sampai' => 'datetime',
        'digunakan_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaranSumber(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_sumber_id');
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query
            ->where('status', StatusVoucher::Aktif)
            ->where('berlaku_sampai', '>=', now());
    }

    public function isUsable(): bool
    {
        return $this->status === StatusVoucher::Aktif
            && $this->berlaku_sampai->isFuture();
    }
}
