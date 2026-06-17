<?php

namespace App\Services\Payment;

use App\Contracts\Services\PaymentServiceInterface;
use App\Contracts\Repositories\PembayaranRepositoryInterface;
use App\Contracts\Services\TagihanServiceInterface;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Voucher;
use App\Enums\StatusPembayaran;
use App\Enums\StatusTagihan;
use App\Services\Voucher\VoucherService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentService implements PaymentServiceInterface
{
    private string $baseUrl;
    private string $secretKey;

    public function __construct(
        private PembayaranRepositoryInterface $pembayaranRepository,
        private TagihanServiceInterface $tagihanService,
        private VoucherService $voucherService
    ) {
        $this->baseUrl = config('services.xendit.base_url', 'https://api.xendit.co');
        $this->secretKey = config('services.xendit.secret_key', '');
    }

    public function createInvoice(Tagihan $tagihan, ?Voucher $voucher = null): array
    {
        $externalId = 'KOS-' . $tagihan->kode_tagihan . '-' . time();
        $expiredAt = now()->addHours(24)->toIso8601String();
        $amounts = $this->voucherService->calculatePaymentAmount($tagihan, $voucher);
        $jumlahBayar = $amounts['jumlah'];

        $payload = [
            'external_id' => $externalId,
            'amount' => $jumlahBayar,
            'description' => "Tagihan Kos — {$tagihan->penyewaan->kamar->nama} Periode " . 
                \Carbon\Carbon::createFromDate($tagihan->periode_tahun, $tagihan->periode_bulan, 1)->format('F Y'),
            'invoice_duration' => 86400, // 24 jam
            'customer' => [
                'given_names' => $tagihan->user->name,
                'email' => $tagihan->user->email,
                'mobile_number' => $tagihan->user->phone,
            ],
            'customer_notification_preference' => [
                'invoice_created' => ['email'],
                'invoice_reminder' => ['email'],
                'invoice_paid' => ['email'],
            ],
            'success_redirect_url' => route('payment.return', ['status' => 'success']),
            'failure_redirect_url' => route('payment.return', ['status' => 'failed']),
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => "Sewa Kos — {$tagihan->penyewaan->kamar->nama}",
                    'quantity' => 1,
                    'price' => $tagihan->jumlah_tagihan,
                    'category' => 'Sewa Kamar',
                ],
            ],
        ];

        // Buat record Pembayaran dulu dengan status pending
        $kodePembayaran = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $pembayaran = DB::transaction(function () use ($tagihan, $kodePembayaran, $externalId, $payload, $expiredAt, $voucher, $amounts) {
            return $this->pembayaranRepository->create([
                'tagihan_id' => $tagihan->id,
                'voucher_id' => $voucher?->id,
                'user_id' => $tagihan->user_id,
                'kode_pembayaran' => $kodePembayaran,
                'xendit_external_id' => $externalId,
                'jumlah' => $amounts['jumlah'],
                'diskon_voucher' => $amounts['diskon_voucher'],
                'biaya_admin' => 0,
                'status' => StatusPembayaran::Pending,
                'payload_request' => $payload,
                'expired_at' => $expiredAt,
            ]);
        });

        // Panggil Xendit API (jika secret key tersedia)
        if (!empty($this->secretKey)) {
            try {
                $response = Http::withBasicAuth($this->secretKey, '')
                    ->post("{$this->baseUrl}/v2/invoices", $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $this->pembayaranRepository->update($pembayaran->id, [
                        'xendit_invoice_id' => $data['id'],
                        'xendit_payment_url' => $data['invoice_url'],
                        'payload_response' => $data,
                    ]);
                    return ['xendit_payment_url' => $data['invoice_url']];
                }

                Log::error('Xendit API error', ['response' => $response->json()]);
            } catch (\Exception $e) {
                Log::error('Xendit API exception', ['error' => $e->getMessage()]);
            }
        }

        return ['pembayaran_id' => $pembayaran->id];
    }

    public function handleWebhook(array $payload): void
    {
        $externalId = $payload['external_id'] ?? null;
        $status = strtolower($payload['status'] ?? '');

        if (!$externalId) return;

        $pembayaran = Pembayaran::where('xendit_external_id', $externalId)->first();
        if (!$pembayaran) {
            Log::warning('Webhook: Pembayaran tidak ditemukan', ['external_id' => $externalId]);
            return;
        }

        DB::transaction(function () use ($pembayaran, $payload, $status) {
            $newStatus = match ($status) {
                'paid', 'settled' => StatusPembayaran::Success,
                'expired' => StatusPembayaran::Expired,
                'failed' => StatusPembayaran::Failed,
                default => StatusPembayaran::Pending,
            };

            $pembayaran->update([
                'status' => $newStatus,
                'payload_webhook' => $payload,
                'jumlah_diterima' => $payload['paid_amount'] ?? null,
                'paid_at' => in_array($status, ['paid', 'settled']) ? now() : null,
                'channel_code' => $payload['payment_channel'] ?? null,
            ]);

            // Tandai tagihan lunas jika pembayaran berhasil
            if ($newStatus === StatusPembayaran::Success) {
                $this->tagihanService->markAsPaid($pembayaran->tagihan_id);

                // Dispatch notification job
                \App\Jobs\SendEmailPaidJob::dispatch($pembayaran->tagihan);
            }
        });
    }
}
