<x-layouts.export.pdf :title="$user->name">
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
                                {{-- Baris piutang dengan rowspan --}}
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
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->jumlah_piutang }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->sisa_piutang }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->terms }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->status_pembayaran }}</td>
                                <td valign="center" style="border: 1px solid #000; padding: 5px;" rowspan="{{ $productCount }}">{{ $piutang->tanggal_lunas ?? 'Belum Lunas' }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    {{-- Tidak punya produk, tapi tetap tampil --}}
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center">{{ $key + 1 }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->kode_piutang }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->nomor_faktur }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->nomor_order }}</td>

                        {{-- Kolom produk kosong --}}
                        <td colspan="3" style="text-align: center; color: gray; border: 1px solid #000; padding: 5px;">Tidak ada produk</td>

                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_transaction }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_jatuh_tempo }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->jumlah_piutang }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->sisa_piutang }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->terms }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->status_pembayaran }}</td>
                        <td valign="center" style="border: 1px solid #000; padding: 5px;">{{ $piutang->tanggal_lunas ?? 'Belum Lunas' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</x-layouts.export.pdf>
