<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Interface\UserInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class UserRepository implements UserInterface
{
    public function getAll()
    {
        return User::with(['customer:id,code_customer,user_id',])
            ->select('id', 'name', 'email', 'created_at')
            ->get();
    }

    public function getFilteredQueryUsers(
        ?string $search = null,
        string $sortBy = 'newest'
    ): Builder {
        return User::query()->with(['roles', 'permissions'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($sortBy === 'latest', fn($q) => $q->orderBy('id', 'asc'))
            ->when($sortBy === 'newest', fn($q) => $q->orderBy('id', 'desc'));
    }

    public function paginateFilteredUsers(
        $search = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryUsers(
            $search,
            $sortBy,
        )->paginate($perPage);
    }
    public function allFilteredUsers(
        $search = null,
        $sortBy = 'newest',
    ) {
        return $this->getFilteredQueryUsers(
            $search,
            $sortBy,
        )->get();
    }



    public function syncUserRoles($user, $roles)
    {
        $user->syncRoles(is_array($roles) ? $roles : [$roles]);
    }
    public function syncUserPermissions($user, array $permission): void
    {
        $user->syncPermissions($permission);
    }

    public function userHasRole()
    {
        // $this->userModel = Auth::User();
        // $hasAllRoles = $this->userModel->hasAllRoles($this->roleModel->all());

        // return $hasAllRoles;
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function getCountUsers()
    {
        $userCount = User::count();
        $precision = $userCount >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($userCount, precision: $precision);
        return $formattedNumber;
    }
}
