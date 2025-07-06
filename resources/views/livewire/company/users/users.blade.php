<div>
    <div>
        <div class="relative mb-6 w-full">
            <flux:heading level="1" size="xl">Users</flux:heading>
            <flux:subheading size="xl" class="mb-6">Manange Your All Users</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    
        <div class="flex md:flex-row flex-col justify-between mb-5 gap-4">
            {{-- <flux:modal.trigger name="create-product">
                <flux:button class="cursor-pointer">Create Product</flux:button>
            </flux:modal.trigger> --}}
    
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
                            <flux:select size="sm" wire:model.lazy="perPage"  placeholder="Pilih Page..." >
                                <flux:select.option value="10">10</flux:select.option>
                                <flux:select.option value="50">50</flux:select.option>
                                <flux:select.option value="100">100</flux:select.option>
                                <flux:select.option value="500">500</flux:select.option>
                            </flux:select>
                        </flux:menu.submenu>
                        <flux:menu.separator />
    
                        <flux:menu.item wire:click="resetFilter"  variant="danger" icon="x-mark">Reset</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Options</flux:button>

                    <flux:menu>
                        <flux:menu.group heading="Export">
                            <flux:menu.item wire:click="downloadPdf" wire:loading.remove wire:loading.attr="disabled" icon="document-arrow-down" class="cursor-pointer">Pdf</flux:menu.item>

                            <flux:menu.item icon="document-arrow-down" wire:click="downloadExcel" wire:loading.remove wire:loading.attr="disabled" class="cursor-pointer">Excel</flux:menu.item>
                        </flux:menu.group>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <div class="my-5">
            <x-loading wire:loading wire:target="downloadPdf, downloadExcel">
                Exporting Users In Progress Please Wait
            </x-loading>

            <div class="space-y-5">
                <x-flash-message />
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
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Permissions
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $key + $users->firstItem() }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->getRoleNames()->implode(', ') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->getPermissionNames()->implode(', ') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div wire:ignore class="flex items-center gap-3">
                                    <flux:button size="sm" variant="primary" class="cursor-pointer" wire:click="edit({{ $user->id }})">Edit</flux:button>
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
        </div>
        <div class="p-5">
            {{ $users->links() }}
        </div>
    
        <livewire:company.users.user-edit /> 
    </div>
    
</div>
