<?php

namespace App\Livewire\Company\Users;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Users')]
class UserEdit extends Component
{
    public UserForm $form;
    #[Computed()]
    public function roles()
    {
        return $this->form->getRoles();
    }

    #[Computed()]
    public function permissions()
    {
        return $this->form->getpermissions();
    }

    #[On('userEdit')]
    public function editUser($id)
    {
        $user = User::find($id);
        $this->form->setUser($user);
        Flux::modal('edit-user')->show();
    }

    public function update()
    {
        $this->form->update();
        Flux::modal('edit-user')->close();
        $this->dispatch('reloadUsers');
    }

    public function render()
    {
        return view('livewire.company.users.user-edit');
    }
}
