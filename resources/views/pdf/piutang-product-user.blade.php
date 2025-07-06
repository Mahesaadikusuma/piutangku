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
        margin-bottom: 20px;
    }

    .logo img {
        width: 50px;
        height: 50px;
    }

    .header {
        text-align: right;
        margin-bottom: 20px;
    }

    .title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        margin-top: 10px;
    }

    .info-table td {
        padding: 3px 5px;
        font-size: 11px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .table th, .table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: left;
        font-size: 11px;
        word-break: break-word;
        white-space: normal;
    }

    .content {
        margin-top: 20px;
    }

    .mt-4 {
        margin-top: 20px;
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
  
      <div class="header">
          {{ \Carbon\Carbon::parse($now)->translatedFormat('d F Y') }}
      </div>
  
      <div class="title">
          LAPORAN PIUTANG CUSTOMER <br>
          {{ $piutang?->user?->setting?->full_name ?? $piutang?->user?->name ?? '-' }}
      </div>
  
      <div class="content">
          <table class="info-table">
              <tr><td style="width: 150px;">Tanggal Cetak</td><td style="width: 10px;">:</td><td>{{ \Carbon\Carbon::parse($now)->translatedFormat('d F Y') }}</td></tr>
              <tr><td>Kode Piutang</td><td>:</td><td>{{ $piutang->kode_piutang }}</td></tr>
              <tr><td>No Faktur</td><td>:</td><td>{{ $piutang->nomor_faktur }}</td></tr>
              <tr><td>No Order</td><td>:</td><td>{{ $piutang->nomor_order }}</td></tr>
              <tr><td>Tanggal Transaksi</td><td>:</td><td>{{ \Carbon\Carbon::parse($piutang->tanggal_transaction)->translatedFormat('d F Y') }}</td></tr>
              <tr><td>Nama Customer</td><td>:</td><td>{{ $piutang->user->name }}</td></tr>
              <tr>
                  <td>Status</td>
                  <td>:</td>
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
            </tr>
              <tr><td>Alamat</td><td>:</td><td>{{ $piutang->user->setting->address ?? '-' }}</td></tr>
          </table>
  
          @if ($piutang->agreement)
              <p>{{ $piutang->agreement->content }}</p>
          @endif
          {{-- <table class="table mt-4">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Produk</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Jumlah</th>
                      <th>Total Piutang</th>
                  </tr>
              </thead>
              <tbody>
                  @php $rowspan = $piutang->products->count(); @endphp
                  @foreach ($piutang->products as $index => $product)
                      <tr>
                          @if ($index === 0)
                              <td rowspan="{{ $rowspan }}">1</td>
                          @endif
                          <td>{{ $product->name }}</td>
                          <td>{{ $product->pivot->qty }}</td>
                          <td>Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                          <td>Rp {{ number_format($product->pivot->qty * $product->pivot->price, 0, ',', '.') }}</td>
                          @if ($index === 0)
                              <td rowspan="{{ $rowspan }}">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                          @endif
                      </tr>
                  @endforeach
              </tbody>
          </table> --}}
          <h1 style="text-align: center; font-weight: bold">
            Piutang Details
          </h1>
          <table class="table mt-4">
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
                    $jumlahPpn = ($piutang->ppn ?? 0) * ($piutang->jumlah_piutang ?? 0) / 100;
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
            <h2>Piutang Products</h2>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total Piutang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($piutang->products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->qty }}</td>
                        <td>Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->pivot->qty * $product->pivot->price, 0, ',', '.') }}</td>
                        <td>
                            @if ($loop->last)
                            Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
        
          
  
          @if ($piutang->agreement)
              <table style="width: 100%; margin-top: 60px;">
                  <tr>
                      <td style="width: 50%; text-align: center;">
                          Mengetahui,<br>
                          <strong>{{ $piutang->agreement->borrower_company }}</strong><br><br><br><br>
                          <strong>{{ $piutang->agreement->borrower_name }}</strong><br>
                          {{ $piutang->agreement->borrower_position }}
                      </td>
                      <td style="width: 50%; text-align: center;">
                          {{ \Carbon\Carbon::parse($piutang->agreement->agreement_date)->translatedFormat('d F Y')}}<br>
                          <strong>{{ $piutang->agreement->leader_company }}</strong><br><br><br><br>
                          <strong>{{ $piutang->agreement->leader_name }}</strong><br>
                          {{ $piutang->agreement->leader_position }}
                      </td>
                  </tr>
              </table>
          @endif
  
          {{-- Page Break jika perlu --}}
          <div class="page-break"></div>
      </div>
  </x-layouts.export.pdf>
  
