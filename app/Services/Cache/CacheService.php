<?php

namespace App\Services\Cache;

use App\Contracts\Services\CacheServiceInterface;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    public function remember(string $key, array $tags, \Closure $callback, int $ttlSeconds = 3600)
    {
        // Use tags if cache store supports it (like Redis/Memcached), fallback if not (e.g. file)
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            return Cache::tags($tags)->remember($key, $ttlSeconds, $callback);
        }

        return Cache::remember($key, $ttlSeconds, $callback);
    }

    public function invalidateTags(array $tags): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            Cache::tags($tags)->flush();
        }
    }

    public function forget(string $key, array $tags = []): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore && !empty($tags)) {
            Cache::tags($tags)->forget($key);
        } else {
            Cache::forget($key);
        }
    }
}
