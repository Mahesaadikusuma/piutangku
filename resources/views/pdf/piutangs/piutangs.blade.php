@push("styles")
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        line-height: 1.6;
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo img {
        margin: -20px;
        width: 50px;
        height: 50px;
    }

    .header {
        text-align: right;
        margin-bottom: 10px;
    }

    .content {
        text-align: justify;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }
</style>
@endpush

<x-layouts.export.pdf title="piutangs">
    <div class="logo">
        <img src="{{ public_path('images/logo.png') }}" alt="logo">
    </div>

    <div class="header">
        <p>Tanggal Cetak {{ Carbon\Carbon::parse($now)->translatedFormat('d F Y')}}</p>
        <p>Page: {{ $piutangs->currentPage()  }}</p>
    </div>

    <div class="" style="text-align: center;">
        <h1>
            DETAIL HISTORICAL PIUTANG CUSTOMER
        </h1>
    </div>

    <div class="content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Kode Piutang</th>
                    <th>No Faktur</th>
                    <th>No Order</th>
                    <th>PPN</th>
                    <th>Jumlah PPN</th>
                    <th>Jumlah Piutang</th>
                    <th>Sisa Piutang</th>
                    <th>Status</th>
                    <th>Tanggal Lunas</th>
                </tr>
            </thead>
            <tbody>
                {{-- @php $no = 1; @endphp
                @forelse ($piutangs as $piutang)
                    @php
                        $jumlahPpn = ($piutang->ppn ?? 0) * ($piutang->jumlah_piutang ?? 0) / 100;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ ($piutangs->currentPage() - 1) * $piutangs->perPage() + $loop->iteration }}</td>
                        <td>{{ $piutang->user->setting->full_name ?? '-' }}</td>
                        <td>{{ $piutang->kode_piutang }}</td>
                        <td>{{ $piutang->nomor_faktur }}</td>
                        <td>{{ $piutang->nomor_order }}</td>
                        <td>{{ $piutang->ppn ?? 0 }}%</td>
                        <td>Rp {{ number_format($jumlahPpn, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                        <td>
                            @switch($piutang->status_pembayaran)
                                @case(App\Enums\StatusType::PENDING->value)
                                <span style="color: orange">{{ $piutang->status_pembayaran }}</span>
                                    @break
                                @case(App\Enums\StatusType::SUCCESS->value)
                                    <span style="color: green">{{ $piutang->status_pembayaran }}</span>
                                    @break

                                @case(App\Enums\StatusType::FAILED->value)
                                    <span style="color: red">{{ $piutang->status_pembayaran }}</span>
                                    @break
                                @default
                                <span style="color: blue">{{ $piutang->status_pembayaran }}</span>
                            @endswitch
                        </td>
                        <td>{{ $piutang->tanggal_lunas ? \Carbon\Carbon::parse($piutang->tanggal_lunas)->translatedFormat('d F Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: center;" colspan="11">No data found</td>
                    </tr>
                @endforelse --}}


                @php
                    $grouped = $piutangs->groupBy(function($item) {
                        return $item->user->id;
                    });
                    $no = 1;
                @endphp

                @forelse ($grouped as $userId => $items)
                    @foreach ($items as $index => $piutang)
                        @php
                            $jumlahPpn = ($piutang->ppn ?? 0) * ($piutang->jumlah_piutang ?? 0) / 100;
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            Simulasi Rowspan: tampilkan hanya di baris pertama
                            @if ($index === 0)
                                <td>{{ $piutang->user->setting->full_name ?? '-' }}</td>
                            @else
                                <td></td>
                            @endif
                            <td>{{ $piutang->kode_piutang }}</td>
                            <td>{{ $piutang->nomor_faktur }}</td>
                            <td>{{ $piutang->nomor_order }}</td>
                            <td>{{ $piutang->ppn ?? 0 }}%</td>
                            <td>Rp {{ number_format($jumlahPpn, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                            <td>
                                @switch($piutang->status_pembayaran)
                                    @case(App\Enums\StatusType::PENDING->value)
                                    <span style="color: orange; font-weight: bold;">{{ $piutang->status_pembayaran }}</span>
                                        @break
                                    @case(App\Enums\StatusType::SUCCESS->value)
                                        <span style="color: green; font-weight: bold;">{{ $piutang->status_pembayaran }}</span>
                                        @break
    
                                    @case(App\Enums\StatusType::FAILED->value)
                                        <span style="color: red; font-weight: bold;">{{ $piutang->status_pembayaran }}</span>
                                        @break
                                    @default
                                    <span style="color: blue; font-weight: bold;">{{ $piutang->status_pembayaran }}</span>
                                @endswitch
                            </td>
                            <td>{{ $piutang->tanggal_lunas ? \Carbon\Carbon::parse($piutang->tanggal_lunas)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                    @endforeach
                    @php $no++; @endphp
                @empty
                    <tr>
                        <td colspan="11">No data found</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</x-layouts.export.pdf>
