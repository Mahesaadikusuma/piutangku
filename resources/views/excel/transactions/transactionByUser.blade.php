<x-layouts.export.pdf>
    <table id="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                {{-- @foreach (['#', 'Kode Piutang', 'Nomor Faktur', 'Nomor Order',  'User Name', 'Product Name', 'Quantity', 'Price', 'Tanggal Transaction', 'Tanggal Jatuh Tempo', 'Jumlah Piutang', 'Sisa Piutang','Jangka Waktu', 'Status', 'Tanggal Lunas',] as $header)
                    <th style="border: 1px solid #000; background-color: yellow; color: black; padding: 5px;">
                        {{ $header }}</th>
                @endforeach --}}
                @foreach (['#', 'Kode Transaksi', 'userName', 'kode piutang', 'Nomimal Pembayaran', 'Total Transaction', 'Status', 'Tanggal Transaction'] as $header)
                    <th style="border: 1px solid #000; background-color: yellow; color: black; padding: 5px;">
                        {{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $key => $transaction)
                @php
                    $piutangCount = $transaction->paymentPiutangs->count();
                @endphp

                @foreach ($transaction->paymentPiutangs as $index => $payment)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ $piutangCount }}"
                                style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center">
                                {{ $key + 1 }}
                            </td>
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->kode }}</td>
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->user->setting->full_name }}</td>
                        @endif

                        <td style="border: 1px solid #000; padding: 5px;" valign="center">
                            {{$payment->piutang->kode_piutang }}
                        </td>
                        <td style="border: 1px solid #000; padding: 5px;" valign="center">
                            {{ $payment->amount }}
                        </td>

                        @if ($index === 0)
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->transaction_total }}</td>
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->status }}</td>
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->created_at }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</x-layouts.export.pdf>