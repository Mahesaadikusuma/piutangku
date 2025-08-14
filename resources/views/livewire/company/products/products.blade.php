<div>
    <div class="relative mb-6 w-full">
        <flux:heading level="1" size="xl">Products</flux:heading>
        <flux:subheading size="xl" class="mb-6">Manange Your All Products</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex md:flex-row flex-col justify-between mb-5 gap-4">
        <flux:modal.trigger name="create-product">
            <flux:button class="cursor-pointer">Create Product</flux:button>
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
                        <flux:select wire:model.lazy="perPage"  placeholder="Choose industry...">
                            <flux:select.option value="10">10</flux:select.option>
                            <flux:select.option value="25">25</flux:select.option>
                            <flux:select.option value="50">50</flux:select.option>
                            <flux:select.option value="100">100</flux:select.option>
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Categories">
                        <flux:select wire:model.lazy="categoryFilter"   placeholder="Pilih Category...">
                            <flux:select.option value=''>All</flux:select.option>
                            @forelse ($this->categories as $category)
                            <flux:select.option :value="$category->id">{{ $category->name }}</flux:select.option>
                            @empty
                            <flux:select.option value="">Not Found Category</flux:select.option>
                            @endforelse
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
                        <flux:menu.item icon="document-arrow-down" wire:click="downloadExcel" wire:loading.remove wire:loading.attr="disabled" class="cursor-pointer">Excel</flux:menu.item>
                    </flux:menu.group>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <div class="my-5">
        <x-loading wire:loading wire:target="downloadPdf, downloadExcel">
            Exporting Or Import In Progress Please Wait
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
                        Kode Product
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Thumbnail
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stock
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $key => $product)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $key + $products->firstItem() }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $product->kode_product }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->category->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="block w-24 text-sm text-gray-500">
                                @if ($product->thumbnail)   
                                    <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
                                @else
                                    <span>No Image</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->stock }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($product->price) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <flux:button size="sm" variant="primary" class="cursor-pointer" wire:click="edit({{ $product->id }})">Edit</flux:button>
                                @can('delete')
                                    <flux:button size="sm" variant="danger" class="cursor-pointer"
                                        wire:click="delete({{ $product->id }})">
                                        Delete ESA
                                    </flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td colspan="8" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5">
        {{ $products->links() }}
    </div>

    <livewire:company.products.product-create />
    <livewire:company.products.product-edit />
    <livewire:company.products.product-delete />
</div>
