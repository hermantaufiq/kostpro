<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$rooms = App\Models\Kamar::select('id','nama','status','kapasitas','show_to_public')->get();
foreach ($rooms as $k) {
    $statusVal = is_object($k->status) ? $k->status->value : $k->status;
    $sisaSlot = $k->sisa_slot;
    $isFullStatus = in_array($statusVal, ['terisi', 'reserved']);
    $isMaint = $statusVal === 'maintenance';
    $isAvail = !$isMaint && !$isFullStatus && $sisaSlot > 0;
    echo $k->id . ' | ' . str_pad($k->nama, 20) . ' | status: ' . str_pad($statusVal, 12) . ' | sisa_slot: ' . $sisaSlot . ' | isAvail: ' . ($isAvail ? 'YES' : 'NO') . PHP_EOL;
}
