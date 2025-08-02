<div>
    <flux:modal name="edit-user" class="min-w-[25rem] md:w-[50rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit User</flux:heading>
                <flux:text class="mt-2">Edit user has role and permissions.</flux:text>
            </div>

            <form wire:submit="update" class="flex flex-col gap-6">
                <flux:input disabled wire:model.lazy="form.name"  label="Name" placeholder="Name User" type="text"  class="cursor-not-allowed" />
                <flux:input disabled wire:model.lazy="form.email"  label="Email" placeholder="Email" type="email" class="cursor-not-allowed" />
                <flux:select  searchable placeholder="Pilih Role..." label="Roles" wire:model.lazy="form.roleId">
                    {{-- <flux:select.option>Pilih Categories</flux:select.option> --}}
                    <flux:select.option value="">Tidak Ada Role</flux:select.option>
                    @foreach ($this->roles as $role)
                        <flux:select.option value="{{$role->id}}">{{$role->name}}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:checkbox.group wire:model="form.permissionsSelected" label="Permissions">
                    @foreach ($this->permissions as $permission)
                        <flux:checkbox :label="$permission->name" :value="$permission->id" />
                    @endforeach
                </flux:checkbox.group>
                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
