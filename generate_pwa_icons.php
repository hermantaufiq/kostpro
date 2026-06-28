<?php

$srcFile = 'C:/Users/ASUS/.gemini/antigravity-ide/brain/807c12dd-6f48-4b9a-a2b2-55c0e9706bd9/kospro_pwa_icon_1782306851426.png';
$destDir = __DIR__ . '/public/icons';

if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];

if (!extension_loaded('gd')) {
    echo "GD not loaded!\n";
    exit(1);
}

$src = imagecreatefrompng($srcFile);
if (!$src) {
    echo "Failed to load source image\n";
    exit(1);
}

$origW = imagesx($src);
$origH = imagesy($src);

echo "Source image: {$origW}x{$origH}\n";

foreach ($sizes as $size) {
    $dst = imagecreatetruecolor($size, $size);
    
    // Enable transparency
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $transparent);
    imagealphablending($dst, true);
    
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $size, $size, $origW, $origH);
    
    $outFile = $destDir . "/icon-{$size}x{$size}.png";
    imagepng($dst, $outFile, 9);
    imagedestroy($dst);
    
    echo "Generated: icon-{$size}x{$size}.png (" . round(filesize($outFile)/1024, 1) . " KB)\n";
}

// Also generate maskable icon (512x512 = same as source but labeled maskable)
copy($destDir . "/icon-512x512.png", $destDir . "/maskable-icon-512x512.png");
echo "Generated: maskable-icon-512x512.png\n";

imagedestroy($src);
echo "\nDone! All PWA icons generated.\n";
