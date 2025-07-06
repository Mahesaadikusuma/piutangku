<?php

namespace App\Livewire\Forms;

use App\Repository\Interface\PermissionInterface;
use App\Repository\PermissionRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission;

class PermissionForm extends Form
{
    public ?Permission $permission;

    #[Validate]
    public string $name = '';

    protected PermissionInterface $permissionRepository;
    public function boot(PermissionInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    protected function rules()
    {
        $id = $this->permission?->id ?? null;
        return [
            'name' => ['required', 'string', 'min:3', 'max:50', 'unique:permissions,name,' . $id],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Permission name harus di isi.',
            'name.min' => 'Minimal characters 3 huruf.',
            'name.max' => 'Permission name terlalu panjang maximal 60 huruf.',
            'name.unique' => 'Permission sudah ada.',
        ];
    }

    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
        $this->name = $permission->name;
    }

    public function store()
    {
        try {
            $this->validate();
            $data = [
                'name' => $this->name
            ];
            $this->permissionRepository->createdPermission($data);
            $this->reset('name');
        } catch (\Exception $e) {
            // Toaster::error('validasi: ' . $e->getMessage());
            Log::error('Terjadi error saat create permission', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }


    public function update()
    {
        try {
            $this->validate();
            $data = [
                'name' => $this->name
            ];
            $this->permissionRepository->updatedPermission($data, $this->permission);
        } catch (\Exception $e) {
            // Toaster::error('validasi: ' . $e->getMessage());
            Log::error('Terjadi error saat update permission', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    public function destroy()
    {
        try {
            $this->permissionRepository->deletedPermission($this->permission);
        } catch (\Exception $e) {
            Log::error('Terjadi error saat delete permission', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
