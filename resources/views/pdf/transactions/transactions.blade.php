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
        margin-bottom: 20px;
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

<x-layouts.export.pdf title="users">
    <div class="logo">
        <img src="{{ public_path('images/logo.png') }}" alt="logo">
    </div>

    <div class="header">
        {{ Carbon\Carbon::parse($now)->translatedFormat('d F Y')}}
    </div>

    <div class="content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Kode Transaksi</th>
                    <th>Kode Piutang</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse ($transactions as $transaction)
                    <tr>
                        {{-- <td>{{ $no++ }}</td> --}}
                        <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                        <td>{{ $transaction->user->setting->full_name ?? '-' }}</td>
                        <td>{{ $transaction->kode }}</td>
                        <td>{{ $transaction->piutang->kode_piutang }}</td>
                        <td>Rp {{ number_format($transaction->transaction_total, 0, ',', '.') }}</td>
                        <td>
                            @switch($transaction->status)
                                @case(App\Enums\StatusType::PENDING->value)
                                <span style="color: orange">{{ $transaction->status }}</span>
                                    @break
                                @case(App\Enums\StatusType::SUCCESS->value)
                                    <span style="color: green">{{ $transaction->status }}</span>
                                    @break

                                @case(App\Enums\StatusType::FAILED->value)
                                    <span style="color: red">{{ $transaction->status }}</span>
                                    @break
                                @default
                                <span style="color: blue">{{ $transaction->status }}</span>
                            @endswitch
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: center;" colspan="11">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.export.pdf>
