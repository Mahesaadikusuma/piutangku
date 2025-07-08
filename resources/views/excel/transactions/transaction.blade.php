<x-layouts.export.pdf>
    <table id="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
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
                    $total_transaction = $transactions->sum('transaction_total');
                @endphp

                @if ($piutangCount > 0)
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

                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $payment->piutang->kode_piutang }}</td>
                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $payment->amount }}
                        </td>

                        @if ($index === 0)
                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $transaction->transaction_total }}</td>
                            @switch($transaction->status)
                                @case(App\Enums\StatusType::PENDING->value)
                                <td rowspan="{{ $piutangCount }}" valign="center" style="border: 1px solid #000; padding: 5px; color: orange">{{ $transaction->status }}</td>
                                    @break
                                @case(App\Enums\StatusType::SUCCESS->value)
                                    <td rowspan="{{ $piutangCount }}" valign="center" style="border: 1px solid #000; padding: 5px; color: green">{{ $transaction->status }}</td>
                                    @break
            
                                @case(App\Enums\StatusType::FAILED->value)
                                    <td rowspan="{{ $piutangCount }}" valign="center" style="border: 1px solid #000; padding: 5px; color: red">{{ $transaction->status }}</td>
                                    @break
                                @default
                                <td rowspan="{{ $piutangCount }}" valign="center" style="border: 1px solid #000; padding: 5px; color: blue">{{ $transaction->status }}</td>
                            @endswitch

                            <td rowspan="{{ $piutangCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center" align="right">
                                {{ $transaction->created_at }}</td>
                        @endif
                    </tr>
                @endforeach

                @else
                    <tr>
                        <td
                            style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center">
                            {{ $key + 1 }}
                        </td>
                        <td style="border: 1px solid #000; padding: 5px;"
                            valign="center">
                            {{ $transaction->kode }}</td>
                        <td style="border: 1px solid #000; padding: 5px;"
                            valign="center">
                            {{ $transaction->user->setting->full_name }}</td>

                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $transaction->piutang->kode_piutang }}</td>
                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $transaction->transaction_total }}
                        </td>

                        
                        <td style="border: 1px solid #000; padding: 5px;"
                            valign="center">
                            {{ $transaction->transaction_total }}</td>
                        @switch($transaction->status)
                            @case(App\Enums\StatusType::PENDING->value)
                            <td valign="center" style="border: 1px solid #000; padding: 5px; color: orange">{{ $transaction->status }}</td>
                                @break
                            @case(App\Enums\StatusType::SUCCESS->value)
                                <td valign="center" style="border: 1px solid #000; padding: 5px; color: green">{{ $transaction->status }}</td>
                                @break
        
                            @case(App\Enums\StatusType::FAILED->value)
                                <td valign="center" style="border: 1px solid #000; padding: 5px; color: red">{{ $transaction->status }}</td>
                                @break
                            @default
                            <td valign="center" style="border: 1px solid #000; padding: 5px; color: blue">{{ $transaction->status }}</td>
                        @endswitch

                        <td style="border: 1px solid #000; padding: 5px;"
                            valign="center" align="right">
                            {{ $transaction->created_at }}</td>
                    </tr>
                @endif
            @endforeach

            <tr>
                <td valign="center" align="center" colspan="5" style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                    Grand Total 
                </td>
                <td align="right" style="border: 1px solid #000; padding: 5px; font-weight: bold;">
                    {{ $total_transaction }}
                </td>
                
                <td colspan="2" style="border: 1px solid #000;"></td>
            </tr>
        </tbody>
    </table>
</x-layouts.export.pdf>