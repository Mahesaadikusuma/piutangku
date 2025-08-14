<div>
    <div class="relative mb-6 w-full">
        <flux:heading level="1" size="xl">Permissions</flux:heading>
        <flux:subheading size="xl" class="mb-6">Manange Your All Permissions</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex md:flex-row flex-col justify-between mb-5 gap-4">
        <flux:modal.trigger name="create-permission">
            <flux:button class="cursor-pointer">Create Permission</flux:button>
        </flux:modal.trigger>

        <div class="flex items-center gap-5">
            <flux:input wire:model.lazy='search' icon="magnifying-glass" placeholder="Search..."/>

            <flux:dropdown>
                <flux:button type="button" icon:trailing="chevron-down">Filter</flux:button>
                <flux:menu>
                    <flux:menu.submenu heading="Sort by">
                        <flux:menu.radio.group wire:model.lazy="sortBy">
                            <flux:menu.radio value="newest">Newest</flux:menu.radio>
                            <flux:menu.radio value="latest">Latest</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Paginate">
                        <flux:select size="sm" wire:model.lazy="perPage"  placeholder="Choose industry..." >
                            <flux:select.option value=''>All</flux:select.option>
                            <flux:select.option value="10">10</flux:select.option>
                            <flux:select.option value="25">25</flux:select.option>
                            <flux:select.option value="50">50</flux:select.option>
                            <flux:select.option value="100">100</flux:select.option>
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.separator />

                    <flux:menu.item wire:click="resetFilter"  variant="danger" icon="x-mark">Reset</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                
                @forelse ($permissions as $key => $permission)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $key + $permissions->firstItem() }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $permission->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @role('admin')
                                    <flux:button size="sm" variant="primary" class="cursor-pointer" wire:click="edit({{ $permission->id }})">Edit</flux:button>
                                    <flux:button size="sm" variant="danger" class="cursor-pointer" wire:click="delete({{ $permission->id }})">Delete</flux:button>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td colspan="3" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="">
            {{ $permissions->links() }}
        </div>
    </div>

    <livewire:company.permissions.permission-create />
    <livewire:company.permissions.permission-edit />
    <livewire:company.permissions.permission-delete />
</div>
