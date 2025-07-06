<?php

namespace App\Livewire\Company\Customers;

use App\Livewire\Forms\CustomerNotNewForm;
use App\Models\Province;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
// #[Title('Customer Create')]
class CustomerNotNewUser extends Component
{
    public CustomerNotNewForm $form;

    public function mount()
    {
        $this->updatedFormUserId($this->form->userId);
    }

    public function store()
    {
        $this->form->store();

        $this->redirect('/company/master-data/customer/customers');
    }

    public function updatedFormUserId($value)
    {
        $this->form->updatedUsersId($value);
    }
    #[Computed()]
    public function users()
    {
        return User::whereDoesntHave('customer')->select('id', 'name', 'email')->get();
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

        return view('livewire.company.customers.customer-not-new-user');
    }
}
