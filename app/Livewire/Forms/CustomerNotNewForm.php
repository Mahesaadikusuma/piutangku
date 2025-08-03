<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Setting;
use App\Models\User;
use App\Models\Village;
use App\Repository\CustomerRepository;
use App\Repository\Interface\CustomerInterface;
use App\Repository\Interface\SettingInterface;
use App\Repository\Interface\UserInterface;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerNotNewForm extends Form
{
    public ?Customer $customer;

    #[Validate()]
    public $userId;
    public $name;
    public $email;
    public $fullName;
    public $phoneNumber;
    public $address;
    public $provinceId;
    public $regencyId;
    public $districtId;
    public $villageId;
    public bool $is_complete = true;
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
        $id = $this->customer?->id ?? null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|exists:users,email',
            'fullName' => 'required|string|max:255',
            'provinceId' => 'required|exists:provinces,id',
            'regencyId' => 'required|exists:regencies,id',
            'districtId' => 'required|exists:districts,id',
            'villageId' => 'required|exists:villages,id',
            'address' => 'required|string|max:500',
            'codeCustomer' => 'required|unique:customers,code_customer,' . $id,
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

    public function store()
    {
        try {
            $this->validate();

            if (Customer::where('user_id', $this->userId)->exists()) {
                throw new \Exception('User already has a customer');
            }

            $setting = $this->settingRepository->updateOrCreateSetting([
                'user_id' => $this->userId,
                'full_name' => $this->fullName,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'address' => $this->address,
                'is_complete' => $this->is_complete,
                'province_id' => $this->provinceId,
                'regency_id' => $this->regencyId,
                'district_id' => $this->districtId,
                'village_id' => $this->villageId,
            ]);

            $this->customerRepository->createCustomer([
                'user_id' => $this->userId,
                'code_customer' => $this->codeCustomer,
                'setting_id' => $setting->id,
            ]);

            session()->flash('success', 'Customer created successfully');
        } catch (\Exception $e) {
            Log::error('error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    public function updatedUsersId($value)
    {
        if (!$value) return;
        $user = User::find($value);
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        } else {
            $this->reset(['name', 'email']);
        }
        $setting = Setting::where('user_id', $value)->first();
        if ($setting) {
            $this->fullName = $setting->full_name;
            $this->phoneNumber = $setting->phone_number;
            $this->address = $setting->address;
            $this->provinceId = $setting->province_id;
            $this->regencyId = $setting->regency_id;
            $this->districtId = $setting->district_id;
            $this->villageId = $setting->village_id;
        } else {
            $this->reset(['fullName', 'phoneNumber', 'address', 'provinceId', 'regencyId', 'districtId', 'villageId']);
        }

        $customer = Customer::where('user_id', $value)->first();
        if ($customer) {
            $this->codeCustomer = $customer->code_customer;
        } else {
            $this->reset(['codeCustomer']);
        }
    }

    public function getUsers()
    {
        return User::whereDoesntHave('customer')->select('id', 'name', 'email')->get();
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
