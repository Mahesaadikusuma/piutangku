


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

        .signature {
            width: 100%;
            margin-top: 40px;
        }

        .signature .right {
            float: right;
            text-align: center;
        }

        p {
            margin: 0 0 8px 0; /* atau 5px sesuai keinginan */
            line-height: 1.5;
        }
        p:empty {
            display: none; /* sembunyikan paragraf kosong (dari <p><br></p>) */
        }

        br {
            margin-top: -20px;
        }

        .ql-editor p {
            margin: 0 0 8px;
        }

    </style>
@endpush

<x-layouts.export.pdf>
    <div class="logo">
        <img src="{{ public_path('images/logo.png') }}" alt="logo">
    </div>

    <div class="header">
        {{ Carbon\Carbon::parse($agreement->agreement_date)->translatedFormat('d F Y')}}
    </div>

    <div class="content">
        <table style="margin-top: 10px; margin-bottom: 20px;">
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 10px;">:</td>
                <td>{{ $agreement->agreement_number }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>{{ $agreement->agreement_lampiran }}</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td>{{ $agreement->agreement_perihal }}</td>
            </tr>
        </table>

        <p>Kepada Yth.<br>
            {{ $agreement->borrower_name }} - {{ $agreement->borrower_position }}<br>
            {{ $agreement->borrower_company }}<br>
            {{ $agreement->borrower_address }}</p>

        <p>Dengan Hormat,</p>

        <div>
            {!! $agreement->content !!}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Piutang</th>
                    <th>Customer</th>
                    <th>Position</th>
                    <th>Company</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $piutang->kode_piutang }}</td>
                    <td>{{ $agreement->borrower_name }}</td>
                    <td>{{ $agreement->borrower_position }}</td>
                    <td>{{ $agreement->borrower_company }}</td>
                </tr>
            </tbody>
        </table>
{{-- 
        <p>
            Kami berharap Bapak/Ibu memberikan izin pengambilan data untuk mahasiswa tersebut.
        </p>

        <p>Demikianlah atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p> --}}
    </div>


    <table style="width: 100%; margin-top: 60px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Mengetahui,<br>
                <strong>{{ $agreement->borrower_company }}</strong><br><br><br><br>
                <strong>{{ $agreement->borrower_name }}</strong><br>
                {{ $agreement->borrower_position }}
            </td>
            <td style="width: 50%; text-align: center;">
                {{ Carbon\Carbon::parse($agreement->agreement_date)->translatedFormat('d F Y')}}<br>
                <strong>{{ $agreement->leader_company }} </strong><br><br><br><br>
                <strong>{{ $agreement->leader_name }}</strong><br>
                {{ $agreement->leader_position }}
            </td>
        </tr>
    </table>


</x-layouts.export.pdf>
