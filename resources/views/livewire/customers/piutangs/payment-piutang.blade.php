<div>
    <flux:heading level="1" size="xl">Piutang Details</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Details</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard-customer')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('transactions.piutang.customer.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Details</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-5">
        <div class="space-y-5">
            <x-flash-message />
        </div>
    </div>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        @if ($piutang->products->count() > 0)
            <flux:heading level="3" size="lg">Product Customer</flux:heading>
            <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                  <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Product Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Quantity</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Price</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach ($piutang->products as $key => $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $key + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $product->pivot->qty }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ number_format($product->pivot->price) }}
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
        @endif

        <flux:heading level="2" size="lg" class="mt-5">Customer {{ $fullName }} - {{ $piutang->user->customer->code_customer }}</flux:heading>
        <form wire:submit='paymentPiutang'>
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
                <flux:button type="submit" class="w-full" variant="primary" color="indigo">Payment</flux:button>
            </div>
        </form>
    </div>
</div>
