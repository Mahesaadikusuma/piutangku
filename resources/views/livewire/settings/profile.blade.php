<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your Profile address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />
            
            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>
            <flux:input wire:model="fullName" :label="__('Full name')" type="text" required autofocus autocomplete="Full name" />
            <flux:input wire:model="phoneNumber" :label="__('Phone Number')" type="text" required autofocus autocomplete="phoneNumber" />
            <flux:field>
                <flux:label>{{ __('Provinsi') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="provinceId" placeholder="Pilih Province...">
                        @foreach ($this->provinces as $province)
                            <flux:select.option :value="$province->id">{{ $province->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="provinceId" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kabupaten / Kota') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="regencyId" placeholder="Pilih Kabupaten / Kota...">
                        @foreach ($this->regencies as $regencie)
                            <flux:select.option :value="$regencie->id">{{ $regencie->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="regencyId" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kecamatan') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="districtId" placeholder="Pilih Kecamatan...">
                        @foreach ($this->districts as $district)
                            <flux:select.option :value="$district->id">{{ $district->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="districtId" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kelurahan') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="villageId" placeholder="Pilih Kelurahan...">
                        @foreach ($this->villages as $village)
                            <flux:select.option :value="$village->id">{{ $village->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="villageId" />
            </flux:field>

            <flux:textarea wire:model.lazy='address'
                label="Address"
                placeholder="Address"
            />


            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
