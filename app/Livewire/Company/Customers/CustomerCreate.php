<?php

namespace App\Livewire\Company\Customers;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Customer Create')]
class CustomerCreate extends Component
{
    public string $userType = 'notNewUser';

    public function setUserType($type)
    {
        if ($type == 'newUser') {
            $this->userType = $type;
        } else if ($type == 'notNewUser') {
            $this->userType = $type;
        } else {
            throw new \Exception("user type not define.");
        }
    }

    public function render()
    {
        return view('livewire.company.customers.customer-create');
    }
}
