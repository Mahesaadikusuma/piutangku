<?php

namespace App\Repository;

use App\Repository\Interface\RoleInterface;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterface
{
    public function getAll()
    {
        return Role::all();
    }

    public function getFilteredQuery($search = null, $sortBy = 'newest')
    {
        return Role::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10)
    {
        return $this->getFilteredQuery($search, $sortBy)->paginate($perPage);
    }

    public function allFiltered($search = null, $sortBy = 'newest')
    {
        return $this->getFilteredQuery($search,  $sortBy)->get();
    }

    public function createdrole(array $data): Role
    {
        return Role::create($data);
    }
    public function updatedrole(array $data, $role): Role
    {
        $role->update($data);
        return $role;
    }
    public function deletedrole($roleId)
    {
        return $roleId->delete();
    }
}
