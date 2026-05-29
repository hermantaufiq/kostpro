<?php

namespace App\Services\Gallery;

use App\Contracts\Services\GalleryServiceInterface;
use App\Models\FotoKamar;
use App\Models\Kamar;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class GalleryService implements GalleryServiceInterface
{
    public function uploadFoto($kamarId, $file, bool $isThumbnail = false)
    {
        $kamar = Kamar::findOrFail($kamarId);
        
        $filename = Str::uuid() . '.webp';
        $path = 'kamar/' . $kamar->kode_kamar . '/' . $filename;
        
        // Convert to WebP
        $image = Image::read($file)->toWebp(80);
        
        Storage::disk('public')->put($path, (string) $image);
        
        return FotoKamar::create([
            'kamar_id' => $kamar->id,
            'foto_path' => $path,
            'foto_url' => Storage::disk('public')->url($path),
            'is_thumbnail' => $isThumbnail,
            'mime_type' => 'image/webp',
        ]);
    }

    public function deleteFoto($fotoId)
    {
        $foto = FotoKamar::findOrFail($fotoId);
        Storage::disk('public')->delete($foto->foto_path);
        return $foto->delete();
    }

    public function setThumbnail($fotoId)
    {
        $foto = FotoKamar::findOrFail($fotoId);
        
        // Reset old thumbnail
        FotoKamar::where('kamar_id', $foto->kamar_id)
            ->where('is_thumbnail', true)
            ->update(['is_thumbnail' => false]);
            
        $foto->update(['is_thumbnail' => true]);
        return $foto;
    }
}
