<?php

namespace App\Services\Cache;

use App\Contracts\Services\CacheServiceInterface;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    /**
     * Get a combined version string for a set of tags.
     * Each tag has its own version counter in cache.
     * When any tag is invalidated, its counter increments,
     * making all old keys that used that tag effectively orphaned.
     */
    private function getTagsVersion(array $tags): string
    {
        $versions = [];
        foreach ($tags as $tag) {
            $versions[$tag] = Cache::get("_tag_ver_{$tag}", 0);
        }
        return md5(implode('|', array_map(fn($t, $v) => "{$t}:{$v}", array_keys($versions), $versions)));
    }

    public function remember(string $key, array $tags, \Closure $callback, int $ttlSeconds = 3600)
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            return Cache::tags($tags)->remember($key, $ttlSeconds, $callback);
        }

        // For non-taggable stores (e.g. file), embed the tags version in the key.
        // When a tag is invalidated its version increments, making this key stale automatically.
        $versionedKey = $key . '__' . $this->getTagsVersion($tags);
        return Cache::remember($versionedKey, $ttlSeconds, $callback);
    }

    public function invalidateTags(array $tags): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            Cache::tags($tags)->flush();
            return;
        }

        // For non-taggable stores: increment the version counter of each tag.
        // All cached items that embedded the old version in their key are now effectively expired.
        foreach ($tags as $tag) {
            $current = Cache::get("_tag_ver_{$tag}", 0);
            Cache::put("_tag_ver_{$tag}", $current + 1, 86400 * 30); // keep version 30 days
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
