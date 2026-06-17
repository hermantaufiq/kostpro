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
        'voucher_id',
        'user_id',
        'kode_pembayaran',
        'xendit_invoice_id',
        'xendit_external_id',
        'xendit_payment_url',
        'metode',
        'channel_code',
        'jumlah',
        'diskon_voucher',
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

    protected static function booted()
    {
        $sendReceipt = function (Pembayaran $pembayaran) {
            if ($pembayaran->status === StatusPembayaran::Success) {
                if ($pembayaran->tagihan && $pembayaran->tagihan->penyewaan && $pembayaran->tagihan->penyewaan->penyewa && $pembayaran->tagihan->penyewaan->penyewa->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($pembayaran->tagihan->penyewaan->penyewa->email)
                            ->send(new \App\Mail\ReceiptEmail($pembayaran));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send ReceiptEmail: ' . $e->getMessage());
                    }
                }
            }
        };

        static::created($sendReceipt);

        static::updated(function (Pembayaran $pembayaran) use ($sendReceipt) {
            if ($pembayaran->isDirty('status')) {
                $sendReceipt($pembayaran);

                if ($pembayaran->status === StatusPembayaran::Success) {
                    $voucherService = app(\App\Services\Voucher\VoucherService::class);

                    if ($pembayaran->voucher_id) {
                        $voucher = \App\Models\Voucher::find($pembayaran->voucher_id);
                        if ($voucher) {
                            $voucherService->markAsUsed($voucher, $pembayaran);
                        }
                    }

                    $voucherService->generateForOnTimePayment($pembayaran);
                }
            }
        });
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
