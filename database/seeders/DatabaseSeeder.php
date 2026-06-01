<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kamar;
use App\Models\FotoKamar;
use App\Enums\StatusKamar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fasilitasUmum = ['AC', 'Kamar Mandi Dalam', 'WiFi 50Mbps', 'Kasur Springbed', 'Lemari Pakaian', 'Meja Belajar', 'Smart TV', 'Water Heater'];

        $kamarData = [
            [
                'kode_kamar' => 'VIP-101',
                'nama' => 'Kamar Tipe VIP - Lantai 1',
                'deskripsi' => 'Kamar kos eksklusif dengan pencahayaan alami yang sangat baik. Nyaman, tenang, dan cocok untuk profesional atau mahasiswa yang butuh privasi ekstra.',
                'harga_bulanan' => 2500000,
                'harga_deposit' => 1000000,
                'tipe' => 'vip',
                'lantai' => 1,
                'luas' => '4x5',
                'fasilitas' => $fasilitasUmum,
                'status' => StatusKamar::Tersedia,
                'is_featured' => true,
            ],
            [
                'kode_kamar' => 'STD-201',
                'nama' => 'Kamar Standard A - Lantai 2',
                'deskripsi' => 'Kamar standard yang sangat nyaman dengan sirkulasi udara yang baik. Cocok untuk budget menengah dengan fasilitas lengkap.',
                'harga_bulanan' => 1500000,
                'harga_deposit' => 500000,
                'tipe' => 'standar',
                'lantai' => 2,
                'luas' => '3x4',
                'fasilitas' => ['Kamar Mandi Luar', 'WiFi 50Mbps', 'Kasur Springbed', 'Lemari', 'Kipas Angin'],
                'status' => StatusKamar::Tersedia,
                'is_featured' => false,
            ],
            [
                'kode_kamar' => 'DLX-202',
                'nama' => 'Kamar Deluxe - Lantai 2',
                'deskripsi' => 'Kamar luas dengan keamanan 24 jam. Bersih, rapi, dan dekat dengan area komunal serta dapur bersama.',
                'harga_bulanan' => 1800000,
                'harga_deposit' => 800000,
                'tipe' => 'deluxe',
                'lantai' => 2,
                'luas' => '3x4',
                'fasilitas' => ['AC', 'Kamar Mandi Luar', 'WiFi 50Mbps', 'Kasur', 'Meja Rias'],
                'status' => StatusKamar::Tersedia,
                'is_featured' => true,
            ],
            [
                'kode_kamar' => 'STE-301',
                'nama' => 'Kamar Premium Suite',
                'deskripsi' => 'Satu-satunya tipe premium di KosPro. Ukuran super luas layaknya apartemen studio kecil.',
                'harga_bulanan' => 3500000,
                'harga_deposit' => 1500000,
                'tipe' => 'suite',
                'lantai' => 3,
                'luas' => '5x6',
                'fasilitas' => array_merge($fasilitasUmum, ['Kulkas Mini', 'Sofa', 'Dapur Kecil Dalam']),
                'status' => StatusKamar::Tersedia,
                'is_featured' => true,
            ],
            [
                'kode_kamar' => 'STD-102',
                'nama' => 'Kamar Standard B (Full)',
                'deskripsi' => 'Kamar standard favorit karena lokasinya yang strategis di dekat pintu masuk utama.',
                'harga_bulanan' => 1400000,
                'harga_deposit' => 500000,
                'tipe' => 'standar',
                'lantai' => 1,
                'luas' => '3x3',
                'fasilitas' => ['Kipas Angin', 'Kasur Single', 'Lemari Kecil', 'WiFi 50Mbps'],
                'status' => StatusKamar::Terisi,
                'is_featured' => false,
            ]
        ];

        foreach ($kamarData as $data) {
            $kamar = Kamar::create($data);

            // Buat foto utama dari gambar mockup yang sudah ada
            FotoKamar::create([
                'kamar_id' => $kamar->id,
                'foto_url' => asset('images/kost-preview.png'),
                'foto_path' => 'images/kost-preview.png',
                'is_thumbnail' => true,
                'sort_order' => 1,
            ]);

            // Tambahkan foto tambahan
            FotoKamar::create([
                'kamar_id' => $kamar->id,
                'foto_url' => asset('images/dashboard-preview.png'),
                'foto_path' => 'images/dashboard-preview.png',
                'is_thumbnail' => false,
                'sort_order' => 2,
            ]);
            
            FotoKamar::create([
                'kamar_id' => $kamar->id,
                'foto_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070&auto=format&fit=crop',
                'foto_path' => 'dummy/unsplash.jpg',
                'is_thumbnail' => false,
                'sort_order' => 3,
            ]);
        }
    }
}
