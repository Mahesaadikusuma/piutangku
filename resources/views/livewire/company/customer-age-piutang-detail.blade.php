<div>
    <flux:heading level="1" size="xl">Customer Age Piutang Detail</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Customer Age Piutang Detail</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Customer Age Piutang Detail</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <div class="border border-gray-200 rounded-lg overflow-auto dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
              <thead class="bg-gray-50 dark:bg-neutral-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Piutang</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Nomor Faktur</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Nomor Order</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Jumlah Piutang</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Status</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Tanggal Transaction</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Tanggal Jatuh Tempo</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Sisa Waktu</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse ($piutangs as $index => $piutang)
                    <tr>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ $index + $piutangs->firstItem() }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ $piutang->kode_piutang }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ $piutang->nomor_faktur }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ $piutang->nomor_order }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ number_format($piutang->jumlah_piutang) }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
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
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ Carbon\Carbon::parse($piutang->tanggal_transaction)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            {{ Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                            @php
                                $now = Carbon\Carbon::now()->startOfDay();
                                $akhirTempo = Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo);
                                $sisaHari = $now->diffInDays($akhirTempo, false);
                            @endphp
                            @if($sisaHari > 0) Sisa {{ $sisaHari }} hari @elseif ($sisaHari === 0) Jatuh tempo hari ini @else Terlambat {{ abs($sisaHari) }} hari @endif
                        </td>
                        <td class="px-6 py-3 text-start text-xs font-medium text-gray-500  dark:text-neutral-400">
                            <div class="flex items-center gap-3">
                            @if ($piutang->products->count() === 0)
                                <flux:button size="xs" :href="route('master-data.piutang.detail', $piutang->id)" variant="filled">Detail</flux:button>
                            @else
                                <flux:button size="xs" :href="route('master-data.piutang-product.detail', $piutang->id)" variant="filled">Detail</flux:button>
                            @endif
                            </div>
                            
                        </td>
                    </tr>
                    @empty
                    <tr class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        <td colspan="10" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                    </tr>
                    @endforelse
                    
              </tbody>
            </table>
        </div>
    </div>
</div>
