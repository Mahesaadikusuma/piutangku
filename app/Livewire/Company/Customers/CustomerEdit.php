<?php

namespace App\Livewire\Company\Customers;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Customer Edit')]
class CustomerEdit extends Component
{
    public CustomerForm $form;

    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->form->setCustomer($customer);
    }

    public function update()
    {
        $this->form->update();
    }

    #[Computed()]
    public function provinces()
    {
        return $this->form->getProvinces();
    }

    #[Computed()]
    public function regencies()
    {
        return $this->form->getRegencies();
    }

    #[Computed()]
    public function districts()
    {
        return $this->form->getDistricts();
    }

    #[Computed()]
    public function villages()
    {
        return $this->form->getVillages();
    }

    public function updatedFormProvinceID($value)
    {
        $this->form->regencyId = null;
        $this->form->districtId = null;
        $this->form->villageId = null;
    }

    public function updatedFormRegencyID($value)
    {
        $this->form->districtId = null;
        $this->form->villageId = null;
    }

    public function updatedFormDistrictID($value)
    {
        $this->form->villageId = null;
    }

    public function render()
    {
        return view('livewire.company.customers.customer-edit');
    }
}
