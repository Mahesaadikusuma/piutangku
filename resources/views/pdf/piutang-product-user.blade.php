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
                <h1>No Invoice <br>
                    NO : {{ $piutang->kode_piutang }}</h1>
            </div>
    
            <table style="width: 100%; margin-top: 10px;" class="table">
                <tr>
                    <td style="width: 50%;">
                        <table class="info-table">
                            <tr>
                                <td style="width: 100px;">To</td>
                                <td style="width: 10px;">:</td>
                                <td>    
                                    {{ $piutang?->user?->setting?->full_name ?? $piutang?->user?->name ?? '-' }} <br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px;">Address</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    {{ $piutang?->user?->setting?->address ?? '-' }} <br>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <table class="info-table">
                            <tr>
                                <td style="width: 120px;">Date Transaction</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($piutang->tanggal_transaction)->translatedFormat('d F Y')}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">No Faktur</td>
                                <td style="width: 10px;">:</td>
                                <td>{{ $piutang->nomor_faktur ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">No Order</td>
                                <td style="width: 10px;">:</td>
                                <td>{{ $piutang->nomor_order ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    
        <div class="content">
            <h1 style="font-weight: bold; font-size: 15px;">
                Piutang Details
            </h1>
    
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Piutang</th>
                        <th>PPN</th>
                        <th>Jumlah PPN</th>
                        <th>Jumlah Piutang</th>
                        <th>Sisa Piutang</th>
                        <th>Jangka Waktu</th>
                        <th>Tanggal Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jumlahPpn = ($piutang->jumlah_piutang ?? 0) * ($piutang->ppn ?? 0) / (100 + ($piutang->ppn ?? 0));
                    @endphp
                    <tr>
                        <td>{{ $piutang->kode_piutang }}</td>
                        <td>{{ $piutang->ppn ?? 0 }}%</td>
                        <td>Rp {{ number_format($jumlahPpn, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                        <td>{{ $piutang->terms }} Hari</td>
                        <td>{{ \Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
                    </tr>
                </tbody>
            </table>
    
            @if ($piutang->products->count() > 0)
                <h1 style="font-weight: bold; font-size: 15px;">
                    Piutang Products
                </h1>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Item</th>
                            <th>Produk / Description</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Unit Price (IDR)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            foreach ($piutang->products as $product) {
                                $total += $product->pivot->qty * $product->pivot->price;
                            }
                            $ppn = ($piutang->ppn ?? 0) * $total / 100;
                        @endphp
    
                        @foreach ($piutang->products as $index => $product)
                            @php
                                $subTotal = $product->pivot->qty * $product->pivot->price;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span>{{ $product->kode_product }}</span><br>
                                    <span>{{ $product->description }}</span>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->qty }}</td>
                                <td>Pcs</td>
                                <td>Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
    
                        <tr>
                            <td colspan="6" align="right" style="padding: 5px; font-weight: bold;">SubTotal</td>
                            <td align="right" style="padding: 5px; font-weight: bold;">
                                {{ number_format($total, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" align="right" style="padding: 5px; font-weight: bold;">PPN ({{ $piutang->ppn }}%)</td>
                            <td align="right" style="padding: 5px; font-weight: bold;">
                                {{ number_format($ppn, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" align="right" style="padding: 5px; font-weight: bold;">Total Amount</td>
                            <td align="right" style="padding: 5px; font-weight: bold;">
                                {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <h5>Please Remit Payment To</h5>
            <table style="width: 100%; margin-top: 10px;" class="table">
                <tr>
                    <td style="width: 50%;">
                        <table class="info-table">
                            <tr>
                                <td style="width: 100px;">Account Name</td>
                                <td style="width: 10px;">:</td>
                                <td>    
                                    <span>PT TAYOH SARANA SUKSES</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px;">Bank</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <span>BCA A YANI - BEKASI</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px;">Account</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <span>066-3055001 (IDR)</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <table class="info-table" style="width: 100%;">
                            <tr>
                                <td style="text-align: center; padding-top: 30px;">
                                    Best Regard<br>
                                    <strong>{{ $piutang->agreement->leader_company ?? 'PT Tayoh Sarana Suksess Dummy' }}</strong><br><br><br><br>
                                    <strong>{{ $piutang->agreement->leader_name ?? 'Muhammad Yani Dummy' }}</strong><br>
                                    {{ $piutang->agreement->leader_position ?? 'Director Dummy' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <p style="text-align: center; text-transform: uppercase; text-decoration: underline; font-weight: bold;">Phone : (021) 82610092, 82610093, 82610094</p>
            <div class="page-break"></div>
        </div>
    </div>
</x-layouts.export.pdf>
