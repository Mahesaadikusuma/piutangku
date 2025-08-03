<div>
    <flux:heading level="1" size="xl">Piutang Product Edit </flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Product Edit {{ $piutang->kode_piutang }}</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang-product.index')">Piutang Products</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Product Edit </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-2">
        <flux:button variant="primary" :href="route('master-data.piutang-product.mou', $piutang->uuid)">
            Mou Piutang
        </flux:button>
    </div>

    @if ($piutang->products->count() === 0)
        <div class="mt-2 mb-2 bg-yellow-500 text-sm text-white rounded-lg p-4" role="alert" tabindex="-1" aria-labelledby="hs-solid-color-warning-label">
            <span id="hs-solid-color-warning-label" class="font-bold">Peringatan</span>: Piutang ini belum memiliki data produk. 
            Harap berhati-hati saat melakukan perubahan agar data tidak hilang.
        </div>
    @endif


    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='update'>
            <div class="flex flex-col gap-5">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                      @if ($hasPayment)
                        <flux:text color="red">Produk tidak dapat diubah karena piutang ini sudah memiliki pembayaran</flux:text>
                        @else
                        <flux:button wire:click='addProduct' variant="primary" class="mb-3 cursor-pointer">
                            Add Product
                        </flux:button>  
                      @endif
  
                      <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                          <thead class="bg-gray-50 dark:bg-neutral-700">
                            <tr>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Product Name</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Quantity</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Price</th>
                              <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Action</th>
                            </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach ($piutangProducts as $index => $piutangProduct)
                              <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200"> 
                                    @if ($hasPayment)
                                    <flux:text>
                                        {{ $this->allProducts->firstWhere('id', $piutangProduct['product_id'])?->name ?? '-' }}
                                    </flux:text>
                                    @else
                                    <flux:select wire:model.lazy="piutangProducts.{{ $index }}.product_id" placeholder="Choose Product...">
                                        <flux:select.option value="">
                                            Pilih Product
                                        </flux:select.option>
                                        @foreach ($this->products($index) as $product)
                                            <flux:select.option :value="$product->id">
                                                {{ $product->name }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    @if ($hasPayment)
                                    <flux:text >
                                        {{ $piutangProduct['qty'] }}
                                    </flux:text>
                                    @else
                                    <flux:input wire:model.lazy="piutangProducts.{{ $index }}.qty"
                                    type="number" :placeholder="__('Quantity')" />
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    @if ($hasPayment)
                                    <flux:text>
                                        {{ $piutangProduct['price'] }}
                                    </flux:text>
                                    @else
                                    <flux:input wire:model.lazy="piutangProducts.{{ $index }}.price"
                                                type="number" :placeholder="__('Price')" />
                                    @endif
                                </td>
                                
                                
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <flux:button wire:click='removeProduct({{ $index }})' variant="danger" class="mb-3 cursor-pointer">
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
                
                
                <flux:input
                    wire:model.lazy="nomorFaktur"
                    :label="__('Nomor Faktur')"
                    type="text"
                    required
                    autocomplete="Nomor-faktur"
                    :placeholder="__('Nomor Faktur')"
                    readOnly
                />
                <flux:input
                    wire:model.lazy="nomorOrder"
                    :label="__('Nomor Order')"
                    type="text"
                    required
                    autocomplete="Nomor-order"
                    :placeholder="__('Nomor Order')"
                    readOnly
                />
                <flux:input
                    wire:model.lazy="customer"
                    :label="__('Customer')"
                    type="text"
                    required
                    autocomplete="customer"
                    :placeholder="__('Customer')"
                    readOnly
                />
                <flux:input
                    wire:model.lazy="ppn"
                    :label="__('PPN')"
                    type="number"
                    required
                    autocomplete="ppn"
                    :placeholder="__('PPN')"
                />

                <div class="" x-data="{ value: @entangle('subtotal') }">
                    <flux:input
                        readonly
                        label="Subtotal"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                    />
                </div>

                <div class="" x-data="{ value: @entangle('ppnAmount') }">
                    <flux:input
                        readonly
                        label="PPN ({{ $ppn }}%)"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                    />
                </div>

                <flux:field>
                    <flux:label>{{ __('Status Pembayaran') }}</flux:label>
                    <flux:input.group>
                        <flux:select wire:model.lazy="statusPembayaran" placeholder="Pilih Status Pembayaran..." :disabled="$piutang->status_pembayaran === \App\Enums\StatusType::SUCCESS->value">
                            @foreach (\App\Enums\StatusType::cases() as $status)
                                <flux:select.option :value="$status->value">
                                    {{ $status->value }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:input.group>

                    <flux:error name="statusPembayaran" />
                </flux:field>

                <div x-data="{ value: @entangle('jumlahPiutang') }">
                    <flux:input
                        readonly
                        :label="__('Jumlah Piutang')"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                        autocomplete="off"
                        readOnly
                    />
                </div>
                <div x-data="{ value: @entangle('sisaHutang') }">
                    <flux:input
                        readonly
                        :label="__('Sisa Hutang')"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                        autocomplete="off"
                        readOnly
                    />
                </div>

                <flux:input type="file" wire:model="proof" label="Bukti Pembayaran"/>

                <flux:input
                    wire:model.lazy="terms" id="terms"
                    :label="__('Jangka Waktu')"
                    type="number"
                    required
                    autocomplete="jangka-waktu"
                    :placeholder="__('Jangka Waktu')"
                />

                <div x-data="tanggalKirim(@entangle('tanggalKirim'))" class="">
                    <flux:input
                        x-ref="tanggalKirim" x-model="value"
                        :label="__('Tanggal Kirim')" id="tanggalKirim"
                        type="date"
                        required
                        autocomplete="tanggal-kirim"
                        :placeholder="__('Tanggal Kirim')"
                    />
                </div>
                <div x-data="tanggalTransaction(@entangle('tanggalTransaction'))" class="">
                    <flux:input
                        x-ref="tanggalTransaction" x-model="value"
                        :label="__('Tanggal Transaction')" id="tanggalTransaction"
                        type="date"
                        required
                        autocomplete="tanggal-jatuh-tempo"
                        :placeholder="__('Tanggal Jatuh Tempo')"
                    />
                </div>
                <div x-data="tanggalJatuhTempo(@entangle('tanggalJatuhTempo'))" class="">
                    <flux:input
                        x-ref="tanggalJatuhTempo" x-model="value" id="tanggalJatuhTempo"
                        :label="__('Tanggal Jatuh Tempo')"
                        type="date"
                        required
                        autocomplete="tanggal-jatuh-tempo"
                        :placeholder="__('Tanggal Jatuh Tempo')"
                    />
                </div>
                <flux:button type="submit" variant="primary" class="w-full">
                    Save
                </flux:button>  
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tanggalKirim', (initialValue) => ({
                value: initialValue,

                init() {
                    // console.log(`Awal Tempo: ${this.value}`);
                    const self = this;
                    new Pikaday({
                        field: this.$refs.tanggalKirim,
                        format: 'YYYY-MM-DD',
                        toString(date, format) {
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${year}-${month}-${day}`;
                        },
                        onSelect(date) {
                            self.value = moment(date).format('YYYY-MM-DD');
                            @this.set('tanggalKirim', self.value, false);
                        }
                    });
                }
            }));
            Alpine.data('tanggalTransaction', (initialValue) => ({
                value: initialValue,

                init() {
                    // console.log(`Awal Tempo: ${this.value}`);
                    const self = this;
                    new Pikaday({
                        field: this.$refs.tanggalTransaction,
                        format: 'YYYY-MM-DD',
                        toString(date, format) {
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${year}-${month}-${day}`;
                        },
                        onSelect(date) {
                            self.value = moment(date).format('YYYY-MM-DD');
                            @this.set('tanggalTransaction', self.value, false);
                            updateAkhirJatuhTempo();
                        }
                    });
                }
            }));

            Alpine.data('tanggalJatuhTempo', (initialValue) => ({
                value: initialValue,

                init() {
                    // console.log(`Akhir Jatuh Tempo: ${this.value}`);
                    const self = this;
                    new Pikaday({
                        field: this.$refs.tanggalJatuhTempo,
                        format: 'YYYY-MM-DD',
                        toString(date, format) {
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${year}-${month}-${day}`;
                        },
                        onSelect(date) {
                            self.value = moment(date).format('YYYY-MM-DD');
                            @this.set('tanggalJatuhTempo', self.value, false);
                            updateTermsFromDates();

                        }
                    });
                }
            }));

            function updateAkhirJatuhTempo() {
                let tanggalTransaction = document.getElementById('tanggalTransaction').value;
                let terms = parseInt(document.getElementById('terms').value) || 0;

                if (tanggalTransaction && terms > 0) {
                    let startDate = new Date(tanggalTransaction);
                    let endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + terms);

                    let formattedEndDate = endDate.toISOString().split('T')[0];
                    document.getElementById('tanggalJatuhTempo').value = formattedEndDate;

                    // Update Livewire property
                    @this.set('tanggalJatuhTempo', formattedEndDate);

                    let akhirJatuhTempoPicker = document.getElementById('tanggalJatuhTempo')._flatpickr;
                    if (akhirJatuhTempoPicker) {
                        akhirJatuhTempoPicker.setDate(formattedEndDate, true);
                    }
                }
            }

            function updateTermsFromDates() {
                let tanggalTransaction = document.getElementById('tanggalTransaction').value;
                let tanggalJatuhTempo = document.getElementById('tanggalJatuhTempo').value;

                if (tanggalTransaction && tanggalJatuhTempo) {
                    let startDate = new Date(tanggalTransaction);
                    let endDate = new Date(tanggalJatuhTempo);

                    // Hitung selisih hari
                    let timeDiff = endDate.getTime() - startDate.getTime();
                    let daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    // Update nilai terms
                    document.getElementById('terms').value = daysDiff;
                    @this.set('terms', daysDiff);
                }
            }

            let termsInput = document.getElementById('terms');
            // document.getElementById('terms').addEventListener('input', updateAkhirJatuhTempo);
            if (termsInput) {
                termsInput.addEventListener('input', updateAkhirJatuhTempo);
            }
        });
    </script>
@endpush