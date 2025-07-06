<div>
    <flux:heading level="1" size="xl">Customer</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Customer Create</flux:subheading>
    <flux:separator variant="subtle" />
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='store'>
            <div class="flex flex-col gap-6">
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
                    wire:model.lazy="form.nomorOrder"
                    :label="__('Nomor Order')"
                    type="text"
                    required
                    autofocus
                    autocomplete="Nomor-order"
                    :placeholder="__('Nomor Order')"
                />

                <flux:field>
                    <flux:label>{{ __('Customers') }}</flux:label>
                    <flux:input.group>
                        <flux:select wire:model.lazy="form.userId" >
                            <flux:select.option value="">Pilih Customer</flux:select.option> 
                            @foreach ($this->customers as $customer)
                                <flux:select.option :value="$customer->user->id">{{ $customer->code_customer }} - {{ $customer->user->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:input.group>

                    <flux:error name="form.userId" />
                </flux:field>

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