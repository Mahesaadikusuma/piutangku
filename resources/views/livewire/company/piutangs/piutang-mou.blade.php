<div>
    <flux:heading level="1" size="xl">Piutang Mou</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Mou</flux:subheading>
    <flux:separator variant="subtle" />
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.piutang.index')">Piutangs</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Piutang Mou</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-2">
        @if ($piutang->agreement)
            <flux:button wire:click='downloadPdf' variant="primary">
                Download Mou
            </flux:button>    
        @endif    
    </div>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='save'>
            <div class="flex flex-col gap-6">
                <flux:input
                    wire:model.lazy="form.nomorDokument"
                    :label="__('Nomor Dokumen')"
                    type="text"
                    required
                    autofocus
                    autocomplete="nomor-dokument"
                    :placeholder="__('AG-001/2025')"
                />
                <flux:input
                    wire:model.lazy="form.lampiran"
                    :label="__('Lampiran')"
                    type="text"
                    required
                    autocomplete="lampiran"
                    :placeholder="__('-')"
                />
                <flux:input
                    wire:model.lazy="form.perihal"
                    :label="__('Perihal')"
                    type="text"
                    required
                    autocomplete="perihal"
                    :placeholder="__('Perihal')"
                />
                <flux:input
                    wire:model.lazy="form.leadCompany"
                    :label="__('Leader Company')"
                    type="text"
                    required
                    autocomplete="lead-company"
                    :placeholder="__('PT. Tayoh Sarana Sukses')"
                />
                <flux:input
                    wire:model.lazy="form.leadName"
                    :label="__('Leader Name')"
                    type="text"
                    required
                    autocomplete="lead-name"
                    :placeholder="__('Budi')"
                />
                <flux:input
                    wire:model.lazy="form.leadPoss"
                    :label="__('Leader Possition')"
                    type="text"
                    required
                    autocomplete="lead-possition"
                    :placeholder="__('Accounting')"
                />

                <flux:input
                    wire:model.lazy="form.browCompany"
                    :label="__('Customer Company')"
                    type="text"
                    required
                    autocomplete="brow-company"
                    :placeholder="__('PT. Budi')"
                />
                <flux:input
                    wire:model.lazy="form.browName"
                    :label="__('Customer Name')"
                    type="text"
                    required
                    autocomplete="brow-name"
                    :placeholder="__('Kusuma')"
                />
                <flux:textarea
                    wire:model.lazy="form.browAddress"
                    label="Address Customer"
                    placeholder="No lettuce, tomato, or onion..."
                />
                <flux:input
                    wire:model.lazy="form.browPoss"
                    :label="__('Customer Position')"
                    type="text"
                    required
                    autocomplete="lead-name"
                    :placeholder="__('Customer Position')"
                />
                <flux:text class="-mt-5">Opsional Input.</flux:text>

                <flux:input
                    wire:model.lazy="form.agreeDate"
                    :label="__('Tanggal Dibuat')"
                    type="date"
                    required
                    autocomplete="tanggal-dibuat"
                    :placeholder="__('tanggal')"
                />
                <flux:textarea
                    wire:model.lazy="form.content"
                    label="Isi Perjanjian"
                    placeholder="No lettuce, tomato, or onion..."
                />

                @if ($piutang->agreement)
                    <flux:input type="file" wire:model="form.generatePdf" label="Simpan Mou"/>
                @endif

                <flux:button type="submit" variant="primary" class="w-full">Save changes</flux:button>
            </div>
        </form>
    </div>

    @if ($piutang->agreement && $piutang->agreement->generated_pdf)
    <iframe src="{{ Storage::url($piutang->agreement->generated_pdf) }}" height="500" width="500" title="Iframe Example"></iframe>
    @endif

</div>
