<?php

namespace App\Livewire\Company\Customers;

use App\Livewire\Forms\CustomerNewForm;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
// #[Title('Customer Create')]
class CustomerNewUser extends Component
{
    public CustomerNewForm $form;

    public function store()
    {
        $this->form->store();
        $this->redirect('/company/master-data/customer/customers');
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
        return view('livewire.company.customers.customer-new-user');
    }
}
