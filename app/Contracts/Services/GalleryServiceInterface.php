<?php

namespace App\Contracts\Services;

interface GalleryServiceInterface
{
    public function uploadFoto($kamarId, $file, bool $isThumbnail = false);
    public function deleteFoto($fotoId);
    public function setThumbnail($fotoId);
}
