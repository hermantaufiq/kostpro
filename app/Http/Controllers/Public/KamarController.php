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
        
        return view('public.kamar.index', compact('rooms', 'filters', 'allFasilitas'));
    }

    public function show($id)
    {
        $room = $this->kamarService->getDetail($id);
        return view('public.kamar.show', compact('room'));
    }
}
