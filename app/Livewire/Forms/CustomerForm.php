<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Repository\CustomerRepository;
use App\Repository\Interface\CustomerInterface;
use App\Repository\Interface\SettingInterface;
use App\Repository\Interface\UserInterface;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Vinkla\Hashids\Facades\Hashids;

class CustomerForm extends Form
{
    public ?Customer $customer;

    #[Validate()]
    public string $email;
    public string $name;
    public string $fullName;
    public $phoneNumber;
    public $address;
    public $provinceId;
    public $regencyId;
    public $districtId;
    public $villageId;
    public $codeCustomer;

    protected CustomerInterface $customerRepository;
    protected SettingInterface $settingRepository;
    protected UserInterface $userRepository;

    public function boot(
        CustomerInterface $customerRepository,
        SettingInterface $settingRepository,
        UserInterface $userRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->settingRepository = $settingRepository;
        $this->userRepository = $userRepository;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|exists:users,email',
            'fullName' => 'required|string|max:255',
            'provinceId' => 'required|exists:provinces,id',
            'regencyId' => 'required|exists:regencies,id',
            'districtId' => 'required|exists:districts,id',
            'villageId' => 'required|exists:villages,id',
            'address' => 'required|string|max:500',
            'codeCustomer' => 'required|unique:customers,code_customer,' . $this->customer?->id,
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email.exists' => 'The email is not registered in our system.',
            'codeCustomer.unique' => 'The customer code has already been taken.',
        ];
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $this->codeCustomer = $customer->code_customer;
        $this->email = $customer->user->email;
        $this->name = $customer->user->name;
        $this->fullName = $customer->setting->full_name;
        $this->phoneNumber = $customer->setting->phone_number;
        $this->provinceId = $customer->setting->province_id;
        $this->regencyId = $customer->setting->regency_id;
        $this->districtId = $customer->setting->district_id;
        $this->villageId = $customer->setting->village_id;
        $this->address = $customer->setting->address;
    }

    public function update()
    {
        try {
            $this->validate();
            $data = [
                'code_customer' => $this->codeCustomer,
                'user_id' => $this->customer->user_id,
                'setting_id' => $this->customer->setting_id,
            ];

            $this->settingRepository->updateOrCreateSetting([
                'user_id' => $this->customer->user_id,
                'full_name' => $this->fullName,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'address' => $this->address,
                'is_complete' => $this->customer->setting->is_complete,
                'province_id' => $this->provinceId,
                'regency_id' => $this->regencyId,
                'district_id' => $this->districtId,
                'village_id' => $this->villageId,
            ]);


            $this->customerRepository->updateCustomer($data, $this->customer);

            session()->flash('success', 'Customer updated successfully');
        } catch (Exception $e) {
            Log::error('error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    public function destroy()
    {
        try {
            $this->customerRepository->deletedCustomer($this->customer);
        } catch (Exception $e) {
            Log::error('error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    public function getProvinces()
    {
        return Province::all();
    }

    public function getRegencies()
    {
        return Regency::where('province_id', $this->provinceId)->get();
    }

    public function getDistricts()
    {
        return District::where('regency_id', $this->regencyId)->get();
    }

    public function getVillages()
    {
        return Village::where('district_id', $this->districtId)->get();
    }
}
