<?php

namespace App\Observers;

use App\Models\Kamar;
use App\Contracts\Services\CacheServiceInterface;

class KamarObserver
{
    public function __construct(
        private CacheServiceInterface $cacheService
    ) {}

    public function saved(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);

        // Sync images array to foto_kamar table
        if (is_array($kamar->images)) {
            $newPaths = [];
            foreach ($kamar->images as $index => $imagePath) {
                $newPaths[] = $imagePath;
                $foto = $kamar->fotoKamar()->firstOrNew(['foto_path' => $imagePath]);
                $foto->foto_url = '/storage/' . $imagePath;
                $foto->is_thumbnail = ($index === 0);
                $foto->sort_order = $index;
                $foto->save();
            }
            
            $kamar->fotoKamar()->whereNotIn('foto_path', $newPaths)->delete();
        }
    }

    public function deleted(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }

    public function restored(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }

    public function forceDeleted(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }
}
