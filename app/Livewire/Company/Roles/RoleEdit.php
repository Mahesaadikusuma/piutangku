<?php

namespace App\Livewire\Company\Roles;

use App\Livewire\Forms\RoleForm;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    public RoleForm $form;

    #[On('roleEdit')]
    public function editRole($id)
    {
        $role = Role::find($id);
        $this->form->setRole($role);
        Flux::modal('edit-role')->show();
    }

    public function update()
    {
        try {
            $this->form->update();
            Flux::modal('edit-role')->close();
            $this->dispatch('reloadRoles');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.roles.role-edit');
    }
}
