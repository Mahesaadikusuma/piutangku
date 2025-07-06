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
#[Title('Permission Edit')]
class PermissionEdit extends Component
{
    public PermissionForm $form;

    #[On('permissionEdit')]
    public function editPermission($id)
    {
        $permission = Permission::find($id);
        $this->form->setPermission($permission);
        Flux::modal('edit-permission')->show();
    }

    public function update()
    {
        try {
            $this->form->update();
            Flux::modal('edit-permission')->close();
            $this->dispatch('reloadPermissions');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.permissions.permission-edit');
    }
}
