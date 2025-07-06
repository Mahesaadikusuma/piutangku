<div>
    <flux:heading level="1" size="xl">Piutang Edit </flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Edit {{ $piutang->kode_piutang }}</flux:subheading>
    <flux:separator variant="subtle" />
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Edit </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-2">
        <flux:button variant="primary" :href="route('master-data.piutang.mou', $piutang->uuid)">
            Mou Piutang
        </flux:button>
    </div>

    @if ($piutang->products->count() > 0)
        <div class="mt-2 mb-2 bg-yellow-500 text-sm text-white rounded-lg p-4" role="alert" tabindex="-1" aria-labelledby="hs-solid-color-warning-label">
            <span id="hs-solid-color-warning-label" class="font-bold">Warning</span> Piutang ini telah memiliki data produk.
            Untuk melakukan perubahan data lainnya, silakan gunakan halaman  <a class="underline" href="{{ route('master-data.piutang-product.index') }}">
                Piutang Product
            </a>
        </div>
    @endif

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='update'>
            <div class="flex flex-col gap-6">
                <flux:input
                    wire:model.lazy="form.kodePiutang"
                    :label="__('Kode Piutang')"
                    type="text"
                    required
                    autocomplete="kode-piutang"
                    :placeholder="__('Kode Piutang')"
                    disabled
                />
                <flux:input
                    wire:model.lazy="form.nomorFaktur"
                    :label="__('Nomor Faktur')"
                    type="text"
                    required
                    autofocus
                    autocomplete="Nomor-faktur"
                    :placeholder="__('Nomor Faktur')"
                />
                <flux:input
                    wire:model.lazy="form.nomorFaktur"
                    :label="__('Nomor Order')"
                    type="text"
                    required
                    autocomplete="Nomor-order"
                    :placeholder="__('Nomor Order')"
                />

                <flux:input
                    wire:model.lazy="form.customer"
                    :label="__('Customer')"
                    type="text"
                    required
                    autocomplete="Nomor-order"
                    :placeholder="__('Customer')"
                    disabled
                />

                <flux:input
                    wire:model.lazy="form.ppn"
                    :label="__('PPN')"
                    type="number"
                    required
                    autocomplete="ppn"
                    :placeholder="__('PPN')"
                />

                <flux:input
                    wire:model.lazy="form.jumlahFaktur"
                    :label="__('Jumlah Piutang')"
                    type="number"
                    required
                    autocomplete="jumlah-piutang"
                    :placeholder="__('Jumlah Piutang')"
                />
                <flux:input
                    wire:model.lazy="form.jumlahPpn"
                    :label="__('Jumlah PPN')"
                    type="number"
                    required
                    autocomplete="ppn"
                    :placeholder="__('Jumlah PPN')"
                    readOnly
                />

                <flux:input
                    wire:model.lazy="form.grandTotal"
                    :label="__('Total Piutang')"
                    type="number"
                    required
                    autocomplete="ppn"
                    :placeholder="__('Total Piutang')"
                    readOnly
                />

                <flux:input
                    wire:model.lazy="form.sisaHutang"
                    :label="__('Sisa Hutang')"
                    type="number"
                    required
                    autocomplete="sisa-piutang"
                    :placeholder="__('Sisa Hutang')"
                    disabled
                />

                <flux:field>
                    <flux:label>{{ __('Status Pembayaran') }}</flux:label>
                    <flux:input.group>
                        <flux:select wire:model.lazy="form.statusPembayaran" placeholder="Pilih Status Pembayaran..." :disabled="$piutang->status_pembayaran === \App\Enums\StatusType::SUCCESS->value">
                            @foreach (\App\Enums\StatusType::cases() as $status)
                                <flux:select.option :value="$status->value">{{ $status->value }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:input.group>

                    <flux:error name="form.statusPembayaran" />
                    <flux:error name="form.userId" />
                </flux:field>

                <flux:input type="file" wire:model="form.buktiPembayaran" label="Bukti Pembayaran"/>

                <flux:input
                    wire:model.lazy="form.terms" id="terms"
                    :label="__('Jangka Waktu')"
                    type="number"
                    required
                    autocomplete="jangka-waktu"
                    :placeholder="__('Jangka Waktu')"
                />
                <div x-data="tanggalTransaction(@entangle('form.tanggalTransaction'))" class="">
                    <flux:input
                        x-ref="tanggalTransaction" x-model="value"
                        :label="__('Tanggal Transaction')" id="tanggalTransaction"
                        type="date"
                        required
                        autocomplete="tanggal-jatuh-tempo"
                        :placeholder="__('Tanggal Jatuh Tempo')"
                    />
                </div>
                <div x-data="tanggalJatuhTempo(@entangle('form.tanggalJatuhTempo'))" class="">
                    <flux:input
                        x-ref="tanggalJatuhTempo" x-model="value" id="tanggalJatuhTempo"
                        :label="__('Tanggal Jatuh Tempo')"
                        type="date"
                        required
                        autocomplete="tanggal-jatuh-tempo"
                        :placeholder="__('Tanggal Jatuh Tempo')"
                    />
                </div>

                <flux:button type="submit" variant="primary" class="w-full">Save changes</flux:button>
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
                            @this.set('form.tanggalTransaction', self.value, false);
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
                            @this.set('form.tanggalJatuhTempo', self.value, false);
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
                    @this.set('form.tanggalJatuhTempo', formattedEndDate);

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
                    @this.set('form.terms', daysDiff);
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
