<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kamarService = app(\App\Contracts\Services\KamarServiceInterface::class);

echo "1. Fetching listing...\n";
$list1 = $kamarService->getListing([], 10);
$firstKamar = $list1->first();
if (!$firstKamar) die("No kamar found.");
$firstId = $firstKamar->id;

echo "   Name in listing: " . $firstKamar->nama . "\n";

echo "2. Updating Kamar directly via Eloquent...\n";
$kamar = \App\Models\Kamar::find($firstId);
$oldName = $kamar->nama;
$newName = $oldName . " - LISTING TEST";
$kamar->nama = $newName;
$kamar->save();

echo "3. Fetching listing again (should have new name)...\n";
$list2 = $kamarService->getListing([], 10);
$updatedKamar = $list2->where('id', $firstId)->first();
echo "   Name in listing: " . $updatedKamar->nama . "\n";

// Revert
$kamar->nama = $oldName;
$kamar->save();
