<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Contracts\Services\PaymentServiceInterface;
use App\Models\Tagihan;
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
            $pembayaran = $this->paymentService->createInvoice($tagihan);
            
            // Xendit invoice returns a redirect URL
            if (!empty($pembayaran['xendit_payment_url'])) {
                return redirect()->away($pembayaran['xendit_payment_url']);
            }

            return redirect()->route('user.tagihan.show', $tagihanId)
                ->with('success', 'Invoice pembayaran berhasil dibuat. Silakan selesaikan pembayaran Anda.');
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

    public function return(Request $request)
    {
        return view('payment.return', [
            'status' => $request->query('status', 'pending'),
        ]);
    }
}
