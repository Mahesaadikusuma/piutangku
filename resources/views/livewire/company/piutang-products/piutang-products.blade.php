<div>
    <div class="relative mb-6 w-full">
        <flux:heading level="1" size="xl">Piutang Products</flux:heading>
        <flux:subheading size="xl" class="mb-6">Manange Your All Piutang</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex md:flex-row flex-col justify-between mb-5 gap-4">
        <flux:button 
            :href="route('master-data.piutang-product.create')" icon:trailing="plus-circle">
            Create
        </flux:button>
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
                            <flux:select.option value="10">10</flux:select.option>
                            <flux:select.option value="50">50</flux:select.option>
                            <flux:select.option value="100">100</flux:select.option>
                            <flux:select.option value="500">500</flux:select.option>
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Status">
                        <flux:select size="sm" wire:model.lazy="status"  placeholder="Pilih Status..." >
                            @foreach (\App\Enums\StatusType::cases() as $status)
                                <flux:select.option :value="$status->value">{{ $status->value }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Customer">
                        <flux:select size="sm" wire:model.lazy="customerFilter"  placeholder="Pilih Customer..." >
                            @forelse ($this->customers as $customer)                    
                            <flux:select.option :value="$customer->user_id">
                                {{ $customer->code_customer }} - {{ $customer->name }}
                            </flux:select.option>
                            @empty
                            <flux:select.option value="">Not Found Customer</flux:select.option>
                            @endforelse
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Years">
                        <flux:select size="sm" wire:model.lazy="years"  placeholder="Pilih Tahun..." >
                            @foreach ($getYears as $year)
                                <flux:select.option :value="$year">{{ $year }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:menu.submenu>
                    <flux:menu.submenu heading="Months">
                        <flux:select size="sm" wire:model.lazy="months"  placeholder="Pilih Bulan..." >
                            @foreach ($getMonths as $number => $month)
                                <flux:select.option :value="$number">{{ $month }}</flux:select.option>
                            @endforeach
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
                        <flux:menu.item icon="document-arrow-down" wire:click="downloadPdf" wire:loading.remove wire:loading.attr="disabled" class="cursor-pointer">Pdf</flux:menu.item>
                    </flux:menu.group>
                    
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <div class="my-5">
        <x-loading wire:loading wire:target="downloadPdf, downloadExcel, downloadPdfById">
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
                        Kode Piutang
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nomer Faktur
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nomer Order
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Customer
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Jumlah Piutang
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal Transaction
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal Jatuh Tempo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($piutangs as $key => $piutang)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $key + $piutangs->firstItem() }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $piutang->kode_piutang }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ $piutang->nomor_faktur }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ $piutang->nomor_order }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ $piutang->user->name }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ number_format($piutang->jumlah_piutang, '0') }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ Carbon\Carbon::parse($piutang->tanggal_transaction)->format('d M Y') }}
                        </td>    
                        <td class="px-6 py-4">
                            {{ Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('d M Y') }}
                        </td>    
                        <td class="px-6 py-4">
                            {{-- {{ $piutang->status_pembayaran }} --}}
                            @switch($piutang->status_pembayaran)
                                @case(App\Enums\StatusType::PENDING->value)
                                <flux:badge variant="solid" color="orange">{{ $piutang->status_pembayaran }}</flux:badge>
                                    @break
                                @case(App\Enums\StatusType::SUCCESS->value)
                                    <flux:badge variant="solid" color="green">{{ $piutang->status_pembayaran }}</flux:badge>
                                    @break

                                @case(App\Enums\StatusType::FAILED->value)
                                    <flux:badge variant="solid" color="red">{{ $piutang->status_pembayaran }}</flux:badge>
                                    @break
                                @default
                                <flux:badge variant="solid" color="Blue">{{ $piutang->status_pembayaran }}</flux:badge>
                            @endswitch
                        </td>    
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($piutang->agreement && $piutang->agreement->generated_pdf)
                                    <flux:button size="sm" icon='arrow-down-tray' wire:click='downloadPdfById({{ $piutang->id }})' variant="filled" class="cursor-pointer">Pdf</flux:button>
                                @else
                                    <flux:tooltip toggleable>
                                        <flux:button icon="information-circle" size="sm" variant="ghost" />

                                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                            <p>Silakan buat dokumen MoU terlebih dahulu.</p>
                                            <p>Anda dapat menambahkannya melalui menu <strong>Edit Piutang → MoU Piutang</strong>.</p>
                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                @endif

                                @if ($piutang->tanggal_kirim)
                                    <flux:tooltip toggleable>
                                        <flux:button icon="truck" size="sm" variant="ghost" />

                                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                            <p>Pengiriman piutang ini dijadwalkan pada tanggal:</p>
                                            <p><strong>{{ \Carbon\Carbon::parse($piutang->tanggal_kirim)->translatedFormat('d F Y') }}</strong></p>
                                            <p>Pastikan tidak ada kendala agar pengiriman dapat dilakukan tepat waktu.</p>
                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                @endif
                                <flux:button size="sm" :href="route('master-data.piutang-product.edit', $piutang->uuid)" variant="ghost" class="cursor-pointer">Edit</flux:button>
                                <flux:button :href="route('master-data.piutang-product.detail', $piutang->uuid)" variant="filled">Detail</flux:button>
                                @if ($piutang->status_pembayaran != App\Enums\StatusType::SUCCESS->value && $piutang->transactions->count() == 0)
                                    @can('delete')
                                        <flux:button size="sm" variant="danger" class="cursor-pointer" wire:click="delete({{ $piutang->id }})">Delete</flux:button>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td colspan="10" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5">
        {{ $piutangs->links() }}
    </div>

    <livewire:company.piutang-products.piutang-product-delete />
</div>
