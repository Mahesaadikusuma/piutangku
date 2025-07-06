<?php

namespace App\Livewire\Company\Roles;

use App\Livewire\Forms\RoleForm;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Roles Create')]
class RoleCreate extends Component
{
    public RoleForm $form;


    public function store()
    {
        try {
            $this->form->store();
            Flux::modal('create-role')->close();
            $this->dispatch('reloadRoles');
        } catch (\Exception $e) {
            throw $e;
        }
    }



    public function render()
    {
        return view('livewire.company.roles.role-create');
    }
}
