<?php
$rooms = App\Models\Kamar::with('fasilitasMaster')->get();
foreach ($rooms as $r) {
    echo $r->id . ' - ' . json_encode($r->fasilitasMaster->pluck('nama')->toArray()) . PHP_EOL;
}
