<?php

namespace App\Repository;

use App\Repository\Interface\RoleInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RoleRepository implements RoleInterface
{
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
        return $this->getFilteredQuery($search, $sortBy)->paginate($perPage);
    }

    public function allFiltered($search = null, $sortBy = 'newest')
    {
        return $this->getFilteredQuery($search, $sortBy)->get();
    }

    public function createdrole(array $data): Role
    {
        $role = Role::create($data);
        return $role;
    }

    public function updatedrole(array $data, $role): Role
    {
        $role->update($data);
        return $role;
    }

    public function deletedrole($role)
    {
        return $role->delete();
    }
}
