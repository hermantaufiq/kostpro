<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\StatusPembayaran;
use App\Enums\MetodePembayaran;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'user_id',
        'kode_pembayaran',
        'xendit_invoice_id',
        'xendit_external_id',
        'xendit_payment_url',
        'metode',
        'channel_code',
        'jumlah',
        'biaya_admin',
        'jumlah_diterima',
        'status',
        'payload_request',
        'payload_response',
        'payload_webhook',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'status' => StatusPembayaran::class,
        'metode' => MetodePembayaran::class,
        'payload_request' => 'array',
        'payload_response' => 'array',
        'payload_webhook' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
