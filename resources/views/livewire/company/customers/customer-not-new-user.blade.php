<div>
    <div class="">
        <form wire:submit="store" class="flex flex-col gap-6">
            {{-- <flux:field>
                <flux:label>{{ __('Pilih User') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.change="form.userId" placeholder="Pilih Users...">
                        @foreach ($this->users as $user)
                            <flux:select.option :value="$user->id">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="form.userId" />
            </flux:field> --}}

            <flux:field wire:ignore>
                <flux:label>{{ __('Pilih Users') }}</flux:label>
                    <select data-hs-select='{
                        "hasSearch": true,
                        "minSearchLength": 2,
                        "searchLimit": 5,
                        "searchPlaceholder": "Search...",
                        "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                        "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                        "placeholder": "Select country...",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                        "dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                        "optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                    }' class="hidden" wire:model.lazy="form.userId" >
                        <option value="">Pilih User</option>
                        @foreach ($this->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                <flux:error name="form.provinceId" />
            </flux:field>

            <flux:input
                wire:model.lazy="form.name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Name')"
                disabled
            />

            <flux:input
                wire:model.lazy="form.email"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
                :placeholder="__('Email Address')"
                disabled
            />

            <div class="">
                <flux:heading size="lg" level="2">Customer profile</flux:heading>
                <flux:text class="mt-2">This information will be displayed publicly.</flux:text>
            </div>

            <flux:input
                wire:model.lazy="form.fullName"
                :label="__('Full Name')"
                type="text"
                required
                autocomplete="full name"
                :placeholder="__('Full Name')"
            />
            <flux:input
                wire:model.lazy="form.phoneNumber"
                :label="__('Phone Number')"
                type="text"
                required
                autocomplete="Phone Number"
                :placeholder="__('Phone Number')"
            />

            <flux:field>
                <flux:label>{{ __('Provinsi') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="form.provinceId" placeholder="Pilih Province...">
                        @foreach ($this->provinces as $province)
                            <flux:select.option :value="$province->id">{{ $province->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>
                <flux:error name="form.provinceId" />
            </flux:field>
            

            <flux:field>
                <flux:label>{{ __('Kabupaten / Kota') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="form.regencyId" placeholder="Pilih Kabupaten / Kota...">
                        @foreach ($this->regencies as $regencie)
                            <flux:select.option :value="$regencie->id">{{ $regencie->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="form.regencyId" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kecamatan') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="form.districtId" placeholder="Pilih Kecamatan...">
                        @foreach ($this->districts as $district)
                            <flux:select.option :value="$district->id">{{ $district->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="form.districtId" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kelurahan') }}</flux:label>
                <flux:input.group>
                    <flux:select wire:model.lazy="form.villageId" placeholder="Pilih Kelurahan...">
                        @foreach ($this->villages as $village)
                            <flux:select.option :value="$village->id">{{ $village->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>

                <flux:error name="form.villageId" />
            </flux:field>

            <flux:textarea wire:model.lazy='form.address'
                label="Address"
                placeholder="Address"
            />

            <flux:input
                wire:model.lazy="form.codeCustomer"
                :label="__('Kode Customer')"
                type="text"
                required
                autocomplete="Kode Customer"
                :placeholder="__('Kode Customer')"
            />

            <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                {{ __('Save changes') }}
            </flux:button>
        </form>
    </div>
</div>
