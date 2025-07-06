<?php

namespace App\Livewire\Company\Permissions;

use App\Livewire\Forms\PermissionForm;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Permission Create')]
class PermissionCreate extends Component
{
    public PermissionForm $form;

    public function store()
    {
        try {
            $this->form->store();
            Flux::modal('create-permission')->close();
            $this->dispatch('reloadPermissions');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.permissions.permission-create');
    }
}
