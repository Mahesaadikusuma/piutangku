<div>
    <div class="">
        <form wire:submit="store" class="flex flex-col gap-6">
            <flux:input
                wire:model.lazy="form.name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <flux:input
                wire:model.lazy="form.email"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
                :placeholder="__('Email address')"
            />
            <flux:input
                wire:model.lazy="form.password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />
            <flux:input
                wire:model="form.password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
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
                autocomplete="full name"
                :placeholder="__('Full Name')"
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
