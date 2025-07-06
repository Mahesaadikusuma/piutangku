<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Repository\Interface\PermissionInterface;
use App\Repository\Interface\RoleInterface;
use App\Repository\Interface\UserInterface;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserForm extends Form
{
    public ?User $user;

    #[Validate]
    public string $name;
    public string $email;
    public $roleId;
    public array $permissionsSelected = [];

    protected UserInterface $userRepository;
    protected RoleInterface $roleRepository;
    protected PermissionInterface $permissionRepository;
    public function boot(UserInterface $userRepository, RoleInterface $roleRepository, PermissionInterface $permissionRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roleId = $user->roles->first()?->id;
        $this->permissionsSelected = $user->permissions->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'sometimes|required|email',
            // 'rolesSelected' => 'array',
            // 'rolesSelected.*' => 'exists:roles,id',
            'permissionsSelected' => 'array',
            'permissionsSelected.*' => 'exists:permissions,id',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama User harus diisi',
            'name.string' => 'Nama User harus berupa string',
            'name.min' => 'Nama User harus minimal 3 karakter',
            'name.max' => 'Nama User maksimal 100 karakter',
            'email.required' => 'Alamat email harus diisi',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            // 'rolesSelected.array' => 'Roles harus berupa array',
            // 'rolesSelected.*.exists' => 'Role yang dipilih tidak valid',
            'permissionsSelected.array' => 'Permissions harus berupa array',
            'permissionsSelected.*.exists' => 'Permission yang dipilih tidak valid',
        ];
    }

    public function update()
    {
        $this->validate();
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $roleName = Role::find($this->roleId)?->name;
        if ($this->roleId) {
            $roleName = Role::find($this->roleId)?->name;
            if ($roleName) {
                $this->userRepository->syncUserRoles($this->user, $roleName);
            } else {
                throw new Exception('Role tidak ditemukan!');
                return;
                // Toaster::error('Role tidak ditemukan!');
            }
        } else {
            // Jika tidak dipilih role, maka hapus semua role user
            $this->user->syncRoles([]);
        }

        $permissions = Permission::whereIn('id', $this->permissionsSelected)->get();
        $permissionsArray = $permissions->pluck('id')->toArray();
        $this->userRepository->syncUserPermissions($this->user, $permissionsArray);
    }


    public function getRoles()
    {
        return $this->roleRepository->getAll();
    }

    public function getpermissions()
    {
        return $this->permissionRepository->getAll();
    }
}
