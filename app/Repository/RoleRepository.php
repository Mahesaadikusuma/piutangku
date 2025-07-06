<?php

namespace App\Repository;

use App\Repository\Interface\RoleInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RoleRepository implements RoleInterface
{
    protected $seconds = 3600;
    protected $baseKey = 'roles';

    public function getAll()
    {
        return Role::all();
    }

    public function getFilteredQuery($search = null, $sortBy = 'newest')
    {
        return Role::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10)
    {
        $cacheKey = $this->generateCacheKey('paginate', $search, $sortBy, $perPage);

        return Cache::remember($cacheKey, $this->seconds, function () use ($search, $sortBy, $perPage) {
            return $this->getFilteredQuery($search, $sortBy)->paginate($perPage);
        });
    }

    public function allFiltered($search = null, $sortBy = 'newest')
    {
        $cacheKey = $this->generateCacheKey('all', $search, $sortBy);

        return Cache::remember($cacheKey, $this->seconds, function () use ($search, $sortBy) {
            return $this->getFilteredQuery($search, $sortBy)->get();
        });
    }

    public function createdrole(array $data): Role
    {
        $role = Role::create($data);
        $this->clearRoleCache();
        return $role;
    }

    public function updatedrole(array $data, $role): Role
    {
        $role->update($data);
        $this->clearRoleCache();
        return $role;
    }

    public function deletedrole($role)
    {
        $this->clearRoleCache();
        return $role->delete();
    }

    protected function clearRoleCache()
    {
        // Clear all cache related to roles
        foreach (Cache::getStore()->getIterator() ?? [] as $key => $value) {
            if (str_contains($key, $this->baseKey)) {
                Cache::forget($key);
            }
        }

        // Alternatif jika driver tidak mendukung getIterator (Redis/file):
        // Cache::flush(); // <- hati-hati! ini hapus semua cache!
    }

    protected function generateCacheKey($prefix, $search = null, $sortBy = 'newest', $perPage = null)
    {
        $generateCacheKey = $this->baseKey . ":{$prefix}:" . md5(json_encode([
            'search' => $search,
            'sortBy' => $sortBy,
            'perPage' => $perPage
        ]));

        Log::info("Generate cache key: {$generateCacheKey}");
        return $generateCacheKey;
    }
}
