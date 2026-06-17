<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Contracts\Services\PenyewaanServiceInterface;
use App\Contracts\Services\KamarServiceInterface;
use App\Contracts\Repositories\PenyewaanRepositoryInterface;
use App\Http\Requests\User\PengajuanSewaRequest;
use App\DTOs\Penyewaan\PengajuanSewaDTO;
use Illuminate\Support\Facades\Auth;

class PenyewaanController extends Controller
{
    public function __construct(
        private PenyewaanServiceInterface $penyewaanService,
        private PenyewaanRepositoryInterface $penyewaanRepository,
        private KamarServiceInterface $kamarService
    ) {}

    public function index()
    {
        $penyewaanList = $this->penyewaanRepository->findByUser(Auth::id());
        return view('user.penyewaan.index', compact('penyewaanList'));
    }

    public function create($kamarId)
    {
        $kamar = $this->kamarService->getDetail($kamarId);
        
        $isMaintenance = $kamar->status->value === 'maintenance';
        $isAllow = !$isMaintenance && ($kamar->sisa_slot > 0 || $kamar->tanggal_tersedia_kembali !== null);

        if (!$isAllow) {
            return redirect()->route('kamar.show', $kamarId)
                ->with('error', 'Kamar ini sedang maintenance atau tidak tersedia untuk disewa.');
        }

        return view('user.penyewaan.create', compact('kamar'));
    }

    public function store(PengajuanSewaRequest $request)
    {
        try {
            $user = Auth::user();

            // Simpan foto KTP jika diunggah
            $ktpPath = $user->foto_ktp_url;
            if ($request->hasFile('foto_ktp')) {
                $ktpPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            // Update profil identitas pengguna
            $user->update([
                'nik'                  => $request->nik,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'alamat'               => $request->alamat,
                'pekerjaan'            => $request->pekerjaan,
                'asal_kota'            => $request->asal_kota,
                'kontak_darurat_nama'  => $request->kontak_darurat_nama,
                'kontak_darurat_hp'    => $request->kontak_darurat_hp,
                'foto_ktp_url'         => $ktpPath,
                'profil_lengkap'       => true,
            ]);

            $dto = new PengajuanSewaDTO(
                user_id: Auth::id(),
                kamar_id: $request->kamar_id,
                tanggal_masuk: $request->tanggal_masuk,
                durasi_bulan: $request->durasi_bulan,
                catatan: $request->catatan
            );

            $penyewaan = $this->penyewaanService->ajukanSewa($dto);

            return redirect()->route('user.penyewaan.show', $penyewaan->id)
                ->with('success', 'Pengajuan sewa berhasil dibuat. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $penyewaan = $this->penyewaanRepository->findById($id, ['*'], ['kamar', 'tagihan', 'approver']);
        
        // Ensure user can only view their own penyewaan
        if ($penyewaan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.penyewaan.show', compact('penyewaan'));
    }

    public function selfService(\Illuminate\Http\Request $request, $id)
    {
        $penyewaan = $this->penyewaanRepository->findById($id);
        
        if ($penyewaan->user_id !== Auth::id()) {
            abort(403);
        }

        $jenis = $request->input('jenis');
        
        if ($jenis === 'pindah') {
            $judul = '[LAPOR PINDAH] - Kamar ' . $penyewaan->kamar->nama;
            $deskripsi = 'Penyewa melaporkan akan pindah/berhenti sewa dari kamar ini.';
        } elseif ($jenis === 'perpanjang') {
            $judul = '[REQUEST PERPANJANG] - Kamar ' . $penyewaan->kamar->nama;
            $deskripsi = 'Penyewa mengajukan perpanjangan durasi sewa kamar ini.';
        } else {
            return back()->with('error', 'Aksi tidak valid.');
        }

        \App\Models\Keluhan::create([
            'user_id' => Auth::id(),
            'kamar_id' => $penyewaan->kamar_id,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'status' => 'menunggu',
            'prioritas' => 'tinggi',
        ]);

        return back()->with('success', 'Permintaan Anda berhasil dikirim ke Admin. Silakan cek menu Keluhan untuk memantau status tiket.');
    }

    public function signContract(\Illuminate\Http\Request $request, $id)
    {
        $penyewaan = $this->penyewaanRepository->findById($id);

        if ($penyewaan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'agree' => 'required|accepted',
            'signature_name' => 'required|string|max:255',
        ]);

        $penyewaan->update([
            'is_contract_signed' => true,
            'signed_at' => now(),
        ]);

        return back()->with('success', 'Kontrak berhasil ditandatangani. Silakan lanjutkan dengan pembayaran tagihan pertama Anda.');
    }
}
