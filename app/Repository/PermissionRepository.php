<?php

namespace App\Repository;

use App\Repository\Interface\PermissionInterface;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterface
{

    public function getAll()
    {
        return Permission::all();
    }

    public function getFilteredQuery($search = null, $sortBy = 'newest')
    {
        return Permission::query()
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

    public function createdPermission(array $data): Permission
    {
        return Permission::create($data);
    }
    public function updatedPermission(array $data, $permission): Permission
    {
        $permission->update($data);
        return $permission;
    }
    public function deletedPermission($PermissionId)
    {
        return $PermissionId->delete();
    }
}
