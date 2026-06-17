<?php
use Illuminate\Support\Str;
$room = App\Models\Kamar::find(8);
if ($room) {
    $ac = App\Models\Fasilitas::firstOrCreate(['nama' => 'AC Split'], ['icon' => 'ac', 'slug' => Str::slug('AC Split')]);
    $meja = App\Models\Fasilitas::firstOrCreate(['nama' => 'Meja Belajar'], ['icon' => 'desk', 'slug' => Str::slug('Meja Belajar')]);
    $lemari = App\Models\Fasilitas::firstOrCreate(['nama' => 'Lemari Pakaian'], ['icon' => 'wardrobe', 'slug' => Str::slug('Lemari Pakaian')]);
    
    $room->fasilitasMaster()->syncWithoutDetaching([$ac->id, $meja->id, $lemari->id]);
    echo 'Fasilitas berhasil ditambahkan!';
}
