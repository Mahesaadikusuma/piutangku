<?php

namespace App\Repository\Interface;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface UserInterface
{
    public function getAll();
    public function getFilteredQueryUsers(
        ?string $search = null,
        string $sortBy = 'newest'
    ): Builder;

    public function paginateFilteredUsers(
        $search = null,
        $sortBy = 'newest',
        $perPage = 10
    );

    public function allFilteredUsers(
        $search = null,
        $sortBy = 'newest',
    );
    public function userHasRole();
    public function createUser(array $data): User;
    public function syncUserRoles($user, array $roles);
    public function syncUserPermissions($user, array $permission): void;
    public function getCountUsers();
}
