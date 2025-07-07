@push("styles")
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        line-height: 1.6;
        margin: 10px;
    }

    .logo {
        text-align: center;
        margin-bottom: 10px;
    }

    .logo img {
        width: 50px;
        height: 50px;
    }

    .header {
        text-align: right;
        margin-bottom: 10px;
    }

    .title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .info-table {
        border: none;
    }

    .info-table td {
        /* padding: 3px 5px; */
        font-size: 11px;
        border: none !important;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: left;
        font-size: 11px;
        word-break: break-word;
        white-space: normal;
    }

    .content {
        margin-top: 10px;
    }

    .mt-4 {
        margin-top: 10px;
    }

    .title .invoice h1 {
        font-size: 20px;
        font-weight: bold;
        align-content: center;
    }

    .title .invoice {
        border: 1px solid #000;
    }

    .page-break {
        page-break-after: always;
    }
</style>
@endpush

<x-layouts.export.pdf>
    <div class="logo">
        <img src="{{ public_path('images/logo.png') }}" alt="logo">
    </div>

    <div style="">
        <div class="title">
            <div class="invoice">
                <h1>No Transaction <br>
                    NO : {{ $transaction->kode }}</h1>
            </div>
        </div>
    
        <div class="content">
                <h1 style="font-weight: bold; font-size: 15px;">
                    Transaction Piutangs
                </h1>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Kode Transaksi</th>
                            <th>Kode Piutang</th>
                            <th>Total Piutang</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction->paymentPiutangs as $index => $payment)
                        <tr>
                            <td scope="row">{{ $index + 1 }}</td>
                            <td scope="row">{{ $transaction?->user?->setting->full_name }}</td>
                            <td align="right">{{ $transaction->kode }}</td>
                            <td align="right">{{ $transaction->piutang->kode_piutang ?? '-' }}</td>
                            <td align="right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td align="right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td scope="row">1</td>
                            <td scope="row">{{ $transaction?->user?->setting->full_name }}</td>
                            <td align="right">{{ $transaction->kode }}</td>
                            <td align="right">{{ $transaction->piutang->kode_piutang }}</td>
                            <td align="right">Rp {{ number_format($transaction->transaction_total, 0, ',', '.') }}</td>
                            <td align="right">Rp {{ number_format($transaction->transaction_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforelse

                        <tr>
                            <td colspan="5" align="right" style="padding: 5px; font-weight: bold;">SubTotal</td>
                            <td align="right" style="padding: 5px; font-weight: bold;">
                                {{ number_format($transaction->transaction_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            <p style="text-align: center; text-transform: uppercase; text-decoration: underline; font-weight: bold;">Phone : (021) 82610092, 82610093, 82610094</p>
            <div class="page-break"></div>
        </div>
    </div>
</x-layouts.export.pdf>
