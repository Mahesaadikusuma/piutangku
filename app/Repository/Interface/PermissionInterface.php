<?php

namespace App\Repository\Interface;

use Spatie\Permission\Models\Permission;

interface PermissionInterface
{
    public function getAll();
    public function getFilteredQuery($search = null, $sortBy = 'newest');
    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10);
    public function allFiltered($search = null, $sortBy = 'newest');
    public function createdPermission(array $data): Permission;
    public function updatedPermission(array $data, $Permission): Permission;
    public function deletedPermission($PermissionId);
}
