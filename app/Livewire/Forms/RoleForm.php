<?php

namespace App\Livewire\Forms;

use App\Repository\Interface\RoleInterface;
use App\Repository\RoleRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Role;

class RoleForm extends Form
{
    public ?Role $role;
    #[Validate]
    public string $name = '';

    protected RoleInterface $roleRepository;

    public function boot(RoleInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    protected function rules()
    {
        $id = $this->role?->id ?? null;
        return [
            'name' => ['required', 'string', 'min:3', 'max:50', 'unique:roles,name,' . $id],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Role name harus di isi.',
            'name.min' => 'Minimal characters 3 huruf.',
            'name.max' => 'role name terlalu panjang maximal 60 huruf.',
            'name.unique' => 'roles sudah ada.',
        ];
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
    }

    public function store()
    {
        try {
            $this->validate();
            $data = [
                'name' => $this->name
            ];
            $this->roleRepository->createdrole($data);
            $this->reset();
        } catch (\Exception $e) {
            // Toaster::error('validasi: ' . $e->getMessage());
            Log::error('Terjadi error saat update role', [
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
            $result = $this->roleRepository->updatedrole($data, $this->role);
        } catch (\Exception $e) {
            // Toaster::error('validasi: ' . $e->getMessage());
            Log::error('Terjadi error saat update role', [
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
            $this->roleRepository->deletedrole($this->role);
        } catch (\Exception $e) {
            Log::error('Terjadi error saat delete role', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
