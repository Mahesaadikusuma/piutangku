<?php

namespace App\Livewire\Company\Customers;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Customers')]
class CustomerDelete extends Component
{
    public CustomerForm $form;


    #[On('customerDelete')]
    public function deleteProduct($id)
    {
        $customer = Customer::find($id);
        $this->form->setCustomer($customer);
        Flux::modal('delete-customer')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-customer')->close();
            $this->dispatch('reloadCustomers');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.customers.customer-delete');
    }
}
