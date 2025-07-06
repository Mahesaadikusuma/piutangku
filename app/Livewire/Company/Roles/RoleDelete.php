<?php

namespace App\Livewire\Company\Roles;

use App\Livewire\Forms\RoleForm;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleDelete extends Component
{
    public RoleForm $form;

    #[On('roleDelete')]
    public function roleDelete($id)
    {
        $role = Role::find($id);
        $this->form->setRole($role);
        Flux::modal('delete-role')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-role')->close();
            $this->dispatch('reloadRoles');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.roles.role-delete');
    }
}
