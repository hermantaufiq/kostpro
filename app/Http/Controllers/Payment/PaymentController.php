<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Contracts\Services\PaymentServiceInterface;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentServiceInterface $paymentService
    ) {}

    public function create(Request $request, $tagihanId)
    {
        $tagihan = Tagihan::where('id', $tagihanId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['unpaid', 'overdue'])
            ->firstOrFail();

        $request->validate([
            'metode' => ['required', 'in:virtual_account,qris,ewallet,credit_card'],
        ]);

        try {
            $result = $this->paymentService->createInvoice($tagihan);
            
            // Jika Xendit sudah dikonfigurasi, redirect ke Xendit checkout
            if (!empty($result['xendit_payment_url'])) {
                return redirect()->away($result['xendit_payment_url']);
            }

            // Mode Simulasi: tampilkan halaman instruksi pembayaran
            $pembayaran = Pembayaran::find($result['pembayaran_id']);
            $pembayaran->update(['metode' => $request->metode]);

            return redirect()->route('payment.instruction', $pembayaran->id);
        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'tagihan_id' => $tagihanId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('user.tagihan.show', $tagihanId)
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function instruction($pembayaranId)
    {
        $pembayaran = Pembayaran::where('id', $pembayaranId)
            ->where('user_id', Auth::id())
            ->with('tagihan.penyewaan.kamar')
            ->firstOrFail();

        // Generate nomor VA simulasi berdasarkan metode
        $metode = $pembayaran->metode?->value ?? 'virtual_account';
        $vaNumber = '8081' . str_pad($pembayaran->tagihan->user_id, 4, '0', STR_PAD_LEFT) . str_pad($pembayaran->id, 8, '0', STR_PAD_LEFT);
        
        $bankOptions = [
            ['kode' => 'BCA',     'va' => '70012' . substr($vaNumber, 5), 'logo_color' => '#003F88'],
            ['kode' => 'BNI',     'va' => '8808' . substr($vaNumber, 4),  'logo_color' => '#f26822'],
            ['kode' => 'BRI',     'va' => '88608' . substr($vaNumber, 5), 'logo_color' => '#00529B'],
            ['kode' => 'Mandiri', 'va' => '89880' . substr($vaNumber, 5), 'logo_color' => '#003087'],
        ];

        return view('payment.instruction', [
            'pembayaran' => $pembayaran,
            'tagihan'    => $pembayaran->tagihan,
            'metode'     => $metode,
            'bankOptions'=> $bankOptions,
            'vaNumber'   => $vaNumber,
            'expiredAt'  => now()->addHours(24),
        ]);
    }

    public function return(Request $request)
    {
        return view('payment.return', [
            'status' => $request->query('status', 'pending'),
        ]);
    }
}
