<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Contracts\Services\KamarServiceInterface;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function __construct(
        private KamarServiceInterface $kamarService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['tipe', 'min_harga', 'max_harga']);
        $rooms = $this->kamarService->getListing($filters, 12);
        
        return view('public.kamar.index', compact('rooms', 'filters'));
    }

    public function show($id)
    {
        $room = $this->kamarService->getDetail($id);
        return view('public.kamar.show', compact('room'));
    }
}
