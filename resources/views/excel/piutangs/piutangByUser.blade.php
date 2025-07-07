<x-layouts.export.pdf :title="$user->name">
    @php
        $total_jumlah_piutang = $piutangs->sum('jumlah_piutang');
        $total_sisa_piutang = $piutangs->sum('sisa_piutang');
    @endphp

    <table id="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                @foreach ([
                    '#', 'Kode Piutang', 'Nomor Faktur', 'Nomor Order', 'Product Name',
                    'Quantity', 'Price', 'Tanggal Transaction', 'Tanggal Jatuh Tempo',
                    'Jumlah Piutang', 'Sisa Piutang', 'Jangka Waktu', 'Status', 'Tanggal Lunas'
                ] as $header)
                    <th style="border: 1px solid #000; background-color: yellow; color: black; padding: 5px;">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($piutangs as $key => $piutang)
                @php $productCount = max($piutang->products->count(), 1); @endphp

                @if ($piutang->products->count() > 0)
                    @foreach ($piutang->products as $index => $product)
                        <tr>
                            @if ($index === 0)
                                <td style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center" rowspan="{{ $productCount }}">{{ $key + 1 }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->kode_piutang }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->nomor_faktur }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->nomor_order }}</td>
                            @endif

                            <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $product->name }}</td>
                            <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $product->pivot->qty }}</td>
                            <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $product->pivot->price }}</td>

                            @if ($index === 0)
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->tanggal_transaction }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->tanggal_jatuh_tempo }}</td>
                                <td valign="center" align="right" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                                <td valign="center" align="right" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->terms }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->status_pembayaran }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->tanggal_lunas ?? 'Belum Lunas' }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    {{-- Tidak punya produk --}}
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center">{{ $key + 1 }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->kode_piutang }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->nomor_faktur }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->nomor_order }}</td>

                        {{-- Produk kosong --}}
                        <td colspan="3" style="text-align: center; color: gray; border: 1px solid #000; padding: 5px;">Tidak ada produk</td>

                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_transaction }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_jatuh_tempo }}</td>
                        <td valign="center" align="right" style="border: 1px solid #000; padding: 5px;">{{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                        <td valign="center" align="right" style="border: 1px solid #000; padding: 5px;">{{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->terms }}</td>
                        {{-- <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->status_pembayaran }}</td> --}}
                        @switch($piutang->status_pembayaran)
                            @case(App\Enums\StatusType::PENDING->value)
                            <td style="border: 1px solid #000; padding: 5px; color: orange">{{ $piutang->status_pembayaran }}</td>
                                @break
                            @case(App\Enums\StatusType::SUCCESS->value)
                                <td style="border: 1px solid #000; padding: 5px; color: green">{{ $piutang->status_pembayaran }}</td>
                                @break

                            @case(App\Enums\StatusType::FAILED->value)
                                <td style="border: 1px solid #000; padding: 5px; color: red">{{ $piutang->status_pembayaran }}</td>
                                @break
                            @default
                            <td style="border: 1px solid #000; padding: 5px; color: blue">{{ $piutang->status_pembayaran }}</td>
                        @endswitch
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_lunas ?? 'Belum Lunas' }}</td>
                    </tr>
                @endif
            @endforeach

            {{-- Grand Total --}}
            <tr>
                <td valign="center" align="center" colspan="9" style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                    Grand Total
                </td>
                <td align="right" style="border: 1px solid #000; padding: 5px; font-weight: bold;">
                    {{ number_format($total_jumlah_piutang, 0, ',', '.') }}
                </td>
                <td align="right" style="border: 1px solid #000; padding: 5px; font-weight: bold;">
                    {{ number_format($total_sisa_piutang, 0, ',', '.') }}
                </td>
                <td colspan="3" style="border: 1px solid #000;"></td>
            </tr>
        </tbody>
    </table>
</x-layouts.export.pdf>
