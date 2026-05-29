<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\StatusPenyewaan;

class Penyewaan extends Model
{
    use SoftDeletes;

    protected $table = 'penyewaan';

    protected $fillable = [
        'user_id',
        'kamar_id',
        'kode_penyewaan',
        'tanggal_masuk',
        'tanggal_keluar',
        'durasi_bulan',
        'status',
        'catatan_penyewa',
        'catatan_admin',
        'approved_by',
        'tanggal_approval',
        'ktp_url',
        'ktp_path',
        'kontrak_url',
        'harga_bulanan_snapshot',
        'deposit_amount',
        'deposit_paid',
        'deposit_paid_at',
        'checkin_at',
        'checkout_at',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'tanggal_approval' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
        'status' => StatusPenyewaan::class,
        'deposit_paid' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
