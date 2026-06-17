<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Contracts\Services\KamarServiceInterface;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function __construct(
        private KamarServiceInterface $kamarService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['gender', 'tipe', 'min_harga', 'max_harga', 'fasilitas']);
        $rooms = $this->kamarService->getListing($filters, 12);
        $allFasilitas = Fasilitas::where('is_active', true)->orderBy('nama')->get();

        // Ambil kamar yang sedang promo aktif untuk banner
        $today = now()->toDateString();
        $promoRooms = \App\Models\Kamar::query()
            ->where('promo_aktif', true)
            ->whereNotNull('harga_promo')
            ->whereColumn('harga_promo', '<', 'harga_bulanan')
            ->where('show_to_public', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('promo_mulai')->orWhere('promo_mulai', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('promo_selesai')->orWhere('promo_selesai', '>=', $today);
            })
            ->with(['thumbnail'])
            ->limit(6)
            ->get();

        return view('public.kamar.index', compact('rooms', 'filters', 'allFasilitas', 'promoRooms'));
    }

    public function show($id)
    {
        $room = $this->kamarService->getDetail($id);
        return view('public.kamar.show', compact('room'));
    }
}
