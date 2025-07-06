<?php

namespace App\Repository\Interface;

interface RoleInterface
{
    public function getAll();
    public function getFilteredQuery($search = null, $sortBy = 'newest');
    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10);
    public function allFiltered($search = null, $sortBy = 'newest');
    public function createdrole(array $data);
    public function updatedrole(array $data, $role);
    public function deletedrole($roleId);
}
