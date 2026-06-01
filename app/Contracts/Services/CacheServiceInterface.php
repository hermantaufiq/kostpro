<?php

namespace App\Contracts\Services;

interface CacheServiceInterface
{
    /**
     * Get or set cache value using tags if supported.
     */
    public function remember(string $key, array $tags, \Closure $callback, int $ttlSeconds = 3600);

    /**
     * Invalidate cache by tags.
     */
    public function invalidateTags(array $tags): void;

    /**
     * Invalidate specific cache key.
     */
    public function forget(string $key, array $tags = []): void;
}
