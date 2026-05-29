<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Contracts\Services\KamarServiceInterface;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __construct(
        private KamarServiceInterface $kamarService
    ) {}

    public function index()
    {
        // Get featured rooms for landing page
        $featuredRooms = $this->kamarService->getListing([], 6);
        return view('welcome', compact('featuredRooms'));
    }
}
