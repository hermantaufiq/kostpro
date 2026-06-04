<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kamarService = app(\App\Contracts\Services\KamarServiceInterface::class);

echo "1. Fetching first kamar in DB...\n";
$firstKamar = \App\Models\Kamar::first();
if (!$firstKamar) die("No kamar found in DB.");
$firstId = $firstKamar->id;

echo "Fetching detail...\n";
$detail1 = $kamarService->getDetail($firstId);
echo "   Name: " . $detail1->nama . "\n";

echo "2. Updating Kamar directly via Eloquent...\n";
$kamar = \App\Models\Kamar::find($firstId);
$oldName = $kamar->nama;
$newName = $oldName . " - EDITED";
$kamar->nama = $newName;
$kamar->save();

echo "3. Fetching detail again (should have new name)...\n";
$detail2 = $kamarService->getDetail($firstId);
echo "   Name in detail: " . $detail2->nama . "\n";

// Revert
$kamar->nama = $oldName;
$kamar->save();
