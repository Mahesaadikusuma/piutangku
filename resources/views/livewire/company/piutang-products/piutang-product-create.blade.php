<div>
    <flux:heading level="1" size="xl">Piutang Product Create</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Product Create</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang-product.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Product Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='store'>
            <div class="flex flex-col gap-5">
                <flux:input
                    wire:model.lazy="nomorFaktur"
                    :label="__('Nomor Faktur')"
                    type="text"
                    required
                    autofocus
                    autocomplete="Nomor-faktur"
                    :placeholder="__('Nomor Faktur')"
                />
                <flux:input
                    wire:model.lazy="nomorOrder"
                    :label="__('Nomor Order')"
                    type="text"
                    required
                    autofocus
                    autocomplete="Nomor-order"
                    :placeholder="__('Nomor Order')"
                />
                <flux:select wire:model.lazy="userId" placeholder="Choose Customer..." label="Customer">
                    <flux:select.option value="">Pilih Customer</flux:select.option> 
                    @foreach ($this->customers as $customer)
                        <flux:select.option :value="$customer->user->id">
                            {{ $customer->code_customer }} - {{ $customer->user->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input
                    wire:model.lazy="ppn"
                    :label="__('PPN')"
                    type="number"
                    required
                    autocomplete="ppn"
                    :placeholder="__('PPN')"
                />
                <div class="-m-1.5 overflow-x-auto">
                  <div class="p-1.5 min-w-full inline-block align-middle">
                    <flux:button wire:click='addProduct' variant="primary" class="mb-3 cursor-pointer">
                        Add Product
                    </flux:button>  

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
                                    <flux:select wire:model.lazy="piutangProducts.{{ $index }}.product_id" placeholder="Choose Product...">
                                        @foreach ($this->products($index) as $product)
                                            <flux:select.option :value="$product->id">
                                                {{ $product->name }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                    <flux:input wire:model.lazy="piutangProducts.{{ $index }}.qty"
                                                type="number" :placeholder="__('Quantity')" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">

                                    @php
                                        $entanglePrice = "@entangle('piutangProducts.{$index}.price').defer";
                                    @endphp
                                    
                                    <div
                                        x-data="{
                                            display: '',
                                            raw: '',
                                            format(value) {
                                                value = value.replace(/\D/g, '');
                                                this.display = new Intl.NumberFormat('id-ID').format(value);
                                                this.raw = value;
                                                $wire.set('piutangProducts.{{ $index }}.price', value);
                                            }
                                        }"
                                        x-init="
                                            raw = {{ $entanglePrice }};
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

                <flux:input
                    wire:model.lazy="terms" id="terms"
                    :label="__('Jangka Waktu')"
                    type="number"
                    required
                    autocomplete="jangka-waktu"
                    :placeholder="__('Jangka Waktu')"
                />

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