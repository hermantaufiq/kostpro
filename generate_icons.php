<?php
$i = imagecreatetruecolor(192, 192);
imagefill($i, 0, 0, imagecolorallocate($i, 79, 70, 229));
imagestring($i, 5, 60, 85, 'KosPro', imagecolorallocate($i, 255, 255, 255));
@mkdir('public/icons', 0777, true);
imagepng($i, 'public/icons/icon-192x192.png');

$i2 = imagecreatetruecolor(512, 512);
imagefill($i2, 0, 0, imagecolorallocate($i2, 79, 70, 229));
imagestring($i2, 5, 220, 250, 'KosPro', imagecolorallocate($i2, 255, 255, 255));
imagepng($i2, 'public/icons/icon-512x512.png');
echo "Icons generated.";
