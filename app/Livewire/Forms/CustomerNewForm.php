<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use App\Repository\CustomerRepository;
use App\Repository\Interface\CustomerInterface;
use App\Repository\Interface\SettingInterface;
use App\Repository\Interface\UserInterface;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rules\Password;

class CustomerNewForm extends Form
{
    public ?Customer $customer;

    #[Validate()]
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
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


    protected function rules(): array
    {
        $id = $this->customer?->id ?? null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
            'password_confirmation' => 'required',
            'fullName' => 'required|string|max:255',
            'provinceId' => 'required|exists:provinces,id',
            'regencyId' => 'required|exists:regencies,id',
            'districtId' => 'required|exists:districts,id',
            'villageId' => 'required|exists:villages,id',
            'address' => 'required|string|max:500',
            'codeCustomer' => 'required|unique:customers,code_customer,' . $id,
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email.exists' => 'The email is not registered in our system.',
            'codeCustomer.unique' => 'The customer code has already been taken.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Password dan konfirmasinya tidak cocok.',
        ];
    }

    public function store()
    {
        try {
            if (User::where('email', $this->email)->exists()) {
                throw new \Exception('Email already exists');
                return;
            }

            $user = $this->userRepository->createUser([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $setting = $this->settingRepository->createSetting([
                'user_id' => $user->id,
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

            $customer = $this->customerRepository->createCustomer([
                'code_customer' => $this->codeCustomer,
                'user_id' => $user->id,
                'setting_id' => $setting->id,
            ]);
            // $this->reset();
            session()->flash('success', 'Customer created successfully');
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
