<div>
    <flux:heading level="1" size="xl">Payment Create</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Payment Create</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('transaction.index')">Transactions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Payment Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='store'>
            <div class="flex flex-col gap-5">
                <div class="-m-1.5 overflow-x-auto">
                  <div class="p-1.5 min-w-full inline-block align-middle">
                    <flux:button wire:click='addPiutang' variant="primary" class="mb-3">
                        add Piutang
                    </flux:button>  
                    <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                      <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-700">
                          <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Customer Name</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Total Piutang</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Sisa Hutang</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Amount Payment</th>
                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Action</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                          @foreach ($transactionPiutangs as $index => $transactionPiutang)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200"> 
                                    <flux:select wire:model.lazy="transactionPiutangs.{{ $index }}.piutang_id" placeholder="Choose Piutang...">
                                        @foreach ($this->piutangs($index) as $piutang)
                                            <flux:select.option :value="$piutang->id">
                                                {{ $piutang->kode_piutang }} - {{ $piutang->user->name }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="transactionPiutangs.{{ $index }}.piutang_id" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    <flux:text>
                                        {{ number_format($transactionPiutang['jumlah_hutang']) }}
                                    </flux:text>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    <flux:text>
                                        {{ number_format($transactionPiutang['sisa_hutang']) }}
                                    </flux:text>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    {{-- <flux:input wire:model.lazy="transactionPiutangs.{{ $index }}.amount"
                                                type="number" :placeholder="__('Jumlah Bayar')" /> --}}

                                    @php
                                        $entangleAmount = "@entangle('transactionPiutangs.{$index}.amount').defer";
                                    @endphp
                                    
                                    <div
                                        x-data="{
                                            display: '',
                                            raw: '',
                                            format(value) {
                                                value = value.replace(/\D/g, '');
                                                this.display = new Intl.NumberFormat('id-ID').format(value);
                                                this.raw = value;
                                                $wire.set('transactionPiutangs.{{ $index }}.amount', value);
                                            }
                                        }"
                                        x-init="
                                            raw = {{ $entangleAmount }};
                                            display = new Intl.NumberFormat('id-ID').format(raw ?? 0);
                                        ">
                                        <flux:input
                                            type="text"
                                            x-model="display"
                                            x-on:input="format($event.target.value)"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            placeholder="Jumlah Bayar"
                                        />
                                    </div>                                
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <flux:button wire:click='removePiutang({{ $index }})' variant="danger" class="mb-3">
                                        Delete
                                    </flux:button>  
                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div x-data="{ value: @entangle('transactionTotal') }">
                    <flux:input
                        readonly
                        :label="__('Transaction Total')"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                        autocomplete="off"
                    />
                </div>
                
                <flux:input
                    wire:model.lazy="jenisPembayaran"
                    :label="__('Jenis Pembayaran')"
                    type="text"
                    required
                    autocomplete="jenis-pembayaran"
                    :placeholder="__('Jenis Pembayaran')"
                />

                <flux:button type="submit" variant="primary" class="w-full">
                    Save
                </flux:button>  
            </div>
        </form>
    </div>
</div>
