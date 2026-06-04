<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Contracts\Repositories\TagihanRepositoryInterface;
use App\Contracts\Repositories\PembayaranRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function __construct(
        private TagihanRepositoryInterface $tagihanRepository,
        private PembayaranRepositoryInterface $pembayaranRepository
    ) {}

    public function index()
    {
        $tagihan = $this->tagihanRepository
            ->firstByCriteria(['user_id' => Auth::id()]);

        $semuaTagihan = \App\Models\Tagihan::where('user_id', Auth::id())
            ->with(['penyewaan.kamar', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.tagihan.index', compact('semuaTagihan'));
    }

    public function show($id)
    {
        $tagihan = \App\Models\Tagihan::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['penyewaan.kamar', 'pembayaran', 'items'])
            ->firstOrFail();

        return view('user.tagihan.show', compact('tagihan'));
    }

    public function print($id)
    {
        $tagihan = \App\Models\Tagihan::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['penyewaan.kamar', 'pembayaran', 'items'])
            ->firstOrFail();

        return view('user.tagihan.print', compact('tagihan'));
    }

    public function downloadPdf($id)
    {
        $tagihan = \App\Models\Tagihan::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['penyewaan.kamar', 'pembayaran', 'items'])
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('tagihan'));
        return $pdf->download('Invoice_KostPro_' . $tagihan->kode_tagihan . '.pdf');
    }
}
