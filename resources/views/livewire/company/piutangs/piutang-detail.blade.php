<div>
    <flux:heading level="1" size="xl">Piutang Detail</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Detail</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Detail</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-2">
        @if ($piutang->agreement && $piutang->agreement->generated_pdf)
            <flux:button variant="primary" :href="Storage::url($piutang->agreement->generated_pdf)">
                Download Mou Piutang
            </flux:button>
        @endif
    </div>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <flux:heading level="2" size="lg">Customer {{ $fullName }} - {{ $piutang->user->customer->code_customer }}</flux:heading>
        <div class="flex flex-col gap-5">
            <flux:input
                wire:model.lazy="fullName"
                :label="__('Full Name')"
                type="text"
                autocomplete="Nomor-faktur"
                :placeholder="__('Full Name')"
                disabled
            />
            <flux:input
                wire:model.lazy="kode"
                :label="__('Kode Piutang')"
                type="text"
                autocomplete="Nomor-faktur"
                :placeholder="__('Kode Piutang')"
                disabled
            />
            <flux:input
                wire:model.lazy="phoneNumber"
                :label="__('Phone Number')"
                type="text"
                autocomplete="phone-number"
                :placeholder="__('08238324')"
                disabled
            />
            <flux:input
                wire:model.lazy="email"
                :label="__('Email')"
                type="text"
                autocomplete="email"
                :placeholder="__('Email')"
                disabled
            />
            <flux:input
                wire:model.lazy="province"
                :label="__('Provinsi')"
                type="text"
                autocomplete="provinsi"
                :placeholder="__('provinsi')"
                disabled
            />
            <flux:input
                wire:model.lazy="regency"
                :label="__('Kabupaten / Kota')"
                type="text"
                autocomplete="regency"
                :placeholder="__('Kabupaten / Kota')"
                disabled
            />
            <flux:input
                wire:model.lazy="district"
                :label="__('Kecamatan')"
                type="text"
                autocomplete="district"
                :placeholder="__('Kecamatan')"
                disabled
            />
            <flux:input
                wire:model.lazy="village"
                :label="__('Kelurahan')"
                type="text"
                autocomplete="village"
                :placeholder="__('Kelurahan')"
                disabled
            />
            <flux:textarea wire:model.lazy="address"
                :label="__('Alamat')"
                :placeholder="__('Alamat')"
                disabled
            />
            <flux:input
                wire:model.lazy="jumlahPiutang"
                :label="__('Jumlah Piutang')"
                type="text"
                autocomplete="jumlah-piutang"
                :placeholder="__('Jumlah Piutang')"
                disabled
            />
            <flux:input
                wire:model.lazy="sisaHutang"
                :label="__('Sisa Piutang')"
                type="text"
                autocomplete="sisa-piutang"
                :placeholder="__('sisa Piutang')"
                disabled
            />
            <flux:field>
                <flux:label>Status</flux:label>

                <flux:text>
                    @switch($status)
                        @case(App\Enums\StatusType::PENDING->value)
                        <flux:badge variant="solid" color="orange">{{ $status }}</flux:badge>
                            @break
                        @case(App\Enums\StatusType::SUCCESS->value)
                            <flux:badge variant="solid" color="green">{{ $status }}</flux:badge>
                            @break
            
                        @case(App\Enums\StatusType::FAILED->value)
                            <flux:badge variant="solid" color="red">{{ $status }}</flux:badge>
                            @break
                        @default
                        <flux:badge variant="solid" color="Blue">{{ $status }}</flux:badge>
                    @endswitch 
                </flux:text>
            </flux:field>
        
            <flux:input
                wire:model.lazy="term"
                :label="__('Jangka Waktu')"
                type="text"
                autocomplete="jangka-waktu"
                :placeholder="__('Jangka Waktu')"
                disabled
            />
            <flux:input
                wire:model.lazy="tanggalTransaction"
                :label="__('Tanggal Transaction')"
                type="text"
                autocomplete="tanggal-transaction"
                :placeholder="__('Tanggal Transaction')"
                disabled
            />
            <flux:input
                wire:model.lazy="tanggalJatuhTempo"
                :label="__('Tanggal Jatuh Tempo')"
                type="text"
                autocomplete="tanggal-jatuh-tempo"
                :placeholder="__('Tanggal Jatuh Tempo')"
                disabled
            />

            <flux:heading level="4" size="lg">Piutang Transaction</flux:heading>
            <div class="border border-gray-200 rounded-lg overflow-auto dark:border-neutral-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                  <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Transaction</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Satatus</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Transaction Total</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Jenis Pembayaran</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Created At</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse ($piutang->transactions as $key => $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $transaction->kode }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    @switch($transaction->status)
                                        @case(App\Enums\StatusType::PENDING->value)
                                        <flux:badge variant="solid" color="orange">{{ $transaction->status }}</flux:badge>
                                            @break
                                        @case(App\Enums\StatusType::SUCCESS->value)
                                            <flux:badge variant="solid" color="green">{{ $transaction->status }}</flux:badge>
                                            @break
                            
                                        @case(App\Enums\StatusType::FAILED->value)
                                            <flux:badge variant="solid" color="red">{{ $transaction->status }}</flux:badge>
                                            @break
                                        @default
                                        <flux:badge variant="solid" color="Blue">{{ $transaction->status }}</flux:badge>
                                    @endswitch 
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ number_format($transaction->transaction_total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $transaction->jenis_pembayaran ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    <div class="flex items-center gap-5">
                                        @if ($transaction->paymentPiutangs->count() > 0)
                                            <flux:button size="xs" variant="primary" class="cursor-pointer" :href="route('transaction.show',$transaction->uuid)">Show</flux:button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <td colspan="7" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                        </tr>
                        @endforelse
                  </tbody>
                </table>
            </div>
        </div>

        @if ($piutang->agreement && $piutang->agreement->generated_pdf)
            <flux:heading class="mt-2">Preview Document</flux:heading>
            <iframe src="{{ Storage::url($piutang->agreement->generated_pdf) }}" class="my-2" height="500" width="500" title="Iframe Example"></iframe>
        @endif
    </div>
</div>
