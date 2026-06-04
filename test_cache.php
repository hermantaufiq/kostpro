<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cacheService = app(\App\Contracts\Services\CacheServiceInterface::class);

echo "1. Getting value...\n";
$val = $cacheService->remember('test_key', ['kamar_list'], function() {
    return "Value " . time();
}, 3600);
echo "Got: $val\n";
sleep(1);
echo "2. Getting value again...\n";
$val2 = $cacheService->remember('test_key', ['kamar_list'], function() {
    return "Value " . time();
}, 3600);
echo "Got: $val2\n";
sleep(1);
echo "3. Invalidating tag...\n";
$cacheService->invalidateTags(['kamar_list']);

echo "4. Getting value after invalidation...\n";
$val3 = $cacheService->remember('test_key', ['kamar_list'], function() {
    return "Value " . time();
}, 3600);
echo "Got: $val3\n";
