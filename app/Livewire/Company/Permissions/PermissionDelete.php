<?php

namespace App\Livewire\Company\Permissions;

use App\Livewire\Forms\PermissionForm;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.app')]
#[Title('Permission Delete')]
class PermissionDelete extends Component
{
    public PermissionForm $form;

    #[On('permissionDelete')]
    public function editRole($id)
    {
        $permission = Permission::find($id);
        $this->form->setPermission($permission);
        Flux::modal('delete-permission')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-permission')->close();
            $this->dispatch('reloadPermissions');
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function render()
    {
        return view('livewire.company.permissions.permission-delete');
    }
}
