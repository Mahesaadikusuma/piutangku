<x-layouts.export.pdf>
    <table id="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                @foreach (['#', 'Kode Piutang', 'Nomor Faktur', 'Nomor Order',  'User Name', 'Product Name', 'Quantity', 'Price', 'Tanggal Transaction', 'Tanggal Jatuh Tempo', 'Jumlah Piutang', 'Sisa Piutang','Jangka Waktu', 'Status', 'Tanggal Lunas',] as $header)
                    <th style="border: 1px solid #000; background-color: yellow; color: black; padding: 5px;">
                        {{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($piutangs as $key => $piutang)
                @php
                    $productCount = $piutang->products->count();
                @endphp

                @foreach ($piutang->products as $index => $product)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ $productCount }}"
                                style="border: 1px solid #000; padding: 5px; text-align: center;" valign="center">
                                {{ $key + 1 }}
                            </td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->kode_piutang }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->nomor_faktur }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->nomor_order }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->user->setting->full_name }}</td>
                        @endif

                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $product->name }}</td>
                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $product->pivot->qty }}
                        </td>
                        <td style="border: 1px solid #000; padding: 5px;" valign="center">{{ $product->pivot->price }}
                        </td>

                        @if ($index === 0)
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->tanggal_transaction }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->tanggal_jatuh_tempo }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->jumlah_piutang }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->sisa_piutang }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->terms }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->status_pembayaran }}</td>
                            <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                {{ $piutang->tanggal_lunas ?? 'Belum Lunas' }}</td>
                            {{-- <td rowspan="{{ $productCount }}" style="border: 1px solid #000; padding: 5px;"
                                valign="center">
                                @if ($piutang->bukti_pembayaran)
                                    <a href="{{ Storage::url($piutang->bukti_pembanyaran) }}"
                                        target="_blank">Download</a>
                                @else
                                    <span style="color: red;">Belum Upload Bukti</span>
                                @endif
                                Bukti Pembayaran
                            </td> --}}
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</x-layouts.export.pdf>