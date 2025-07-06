<?php

namespace App\Livewire\Settings;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Setting;
use App\Models\User;
use App\Models\Village;
use App\Repository\SettingRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile')]
class Profile extends Component
{
    public Setting $setting;
    public string $name = '';

    public string $email = '';
    public $fullName;
    public $phoneNumber;
    public $address;
    public $avatar = null;
    public bool $isComplete = true;
    public $provinceId;
    public $regencyId;
    public $districtId;
    public $villageId;
    public bool $is_complete = true;

    protected SettingRepository $settingRepository;
    public function boot(
        SettingRepository $settingRepository,
    ) {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;

        $this->setting = $this->settingRepository->getUserSetting();
        $this->fullName = $this->setting->full_name;
        $this->phoneNumber = $this->setting->phone_number;
        $this->provinceId = $this->setting->province_id;
        $this->regencyId = $this->setting->regency_id;
        $this->districtId = $this->setting->district_id;
        $this->villageId = $this->setting->village_id;
        $this->address = $this->setting->address;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $data = [
            'user_id' => $user->id,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'address' => $this->address,
            "is_complete" => $this->isComplete ? true : false,
            'province_id' => $this->provinceId,
            'regency_id' => $this->regencyId,
            'district_id' => $this->districtId,
            'village_id' => $this->villageId,
        ];
        $this->settingRepository->saveSetting($data, $this->avatar);
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed()]
    public function provinces()
    {
        return Province::all();
    }

    #[Computed()]
    public function regencies()
    {
        return Regency::where('province_id', $this->provinceId)->get();
    }

    #[Computed()]
    public function districts()
    {
        return District::where('regency_id', $this->regencyId)->get();
    }

    #[Computed()]
    public function villages()
    {
        return Village::where('district_id', $this->districtId)->get();
    }

    public function updatedFormProvinceId($value)
    {
        $this->regencyId = null;
        $this->districtId = null;
        $this->villageId = null;
    }

    public function updatedFormRegencyId($value)
    {
        $this->districtId = null;
        $this->villageId = null;
    }

    public function updatedFormDistrictId($value)
    {
        $this->villageId = null;
    }
}
