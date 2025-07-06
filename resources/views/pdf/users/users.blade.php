@push("styles")
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 10px;
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
                    <th>No</th>
                    <th>User Name</th>
                    <th>Role</th>
                    <th>Permission</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        {{-- ($users->currentPage() - 1): posisi halaman sekarang dikurangi 1.
                        * $users->perPage(): jumlah data sebelum halaman ini.
                        + $loop->iteration: nomor urutan dalam halaman saat ini. --}}
                        {{--  (1-1)*10 + 1 = 1 --}}
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') ?? 'N/A' }}</td>
                        <td>{{ $user->getPermissionNames()->implode(', ') ?? 'N/A' }}</td>
                    </tr>
                @empty
                <tr>
                    <td colspan="4">
                        Not Found User
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.export.pdf>
