<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\App\Models\Setting::updateOrCreate(
    ['key' => 'alamat_kos'],
    ['value' => 'Jl. Taman Siswa, Pekeng, Kauman, Tahunan, Kec. Tahunan, Kabupaten Jepara, Jawa Tengah 59451']
);

\App\Models\Setting::updateOrCreate(
    ['key' => 'map_iframe'],
    ['value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15856.2413155822!2d110.6617945!3d-6.6025175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e711ef0dbfc9c3d%3A0xc66518776bd3e3fc!2sTahunan%2C%20Kec.%20Tahunan%2C%20Kabupaten%20Jepara%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="400" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>']
);

echo "Settings updated successfully.\n";
