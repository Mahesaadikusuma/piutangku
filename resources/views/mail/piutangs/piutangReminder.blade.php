<x-mail::message>
# Order Shipped

Halo {{ $user->name }}, berikut adalah daftar piutang Anda yang akan jatuh tempo:

<x-mail::panel>
Daftar Hutang Jatuh Tempo : {{ $piutangs->count() }}
</x-mail::panel>

<x-mail::table>
| kode piutang   | Jatuh Tempo   | Sisa Hari    | Status   |
| :-----------: | :----------:  | :--------:   | :------: | 
@foreach ($piutangs as $piutang)
@php
$now = Carbon\Carbon::now()->startOfDay();
$akhirTempo = Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo);
$sisaHari = $now->diffInDays($akhirTempo, false);
@endphp
| {{ $piutang->kode_piutang }} |  {{ \Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('d M Y') }} | @if($sisaHari > 0) Sisa {{ $sisaHari }} hari @elseif ($sisaHari === 0) Jatuh tempo hari ini @else Terlambat {{ abs($sisaHari) }} hari @endif | {{ $piutang->status_pembayaran }} 
@endforeach
</x-mail::table>

<x-slot:subcopy>
Silakan melakukan pembayaran sebelum tanggal jatuh tempo.

@if ($user->roles->first()?->name == 'admin' || $user->roles->first()?->name == 'company')
@if ($piutang->products->count() > 0)
<x-mail::button :url="route('master-data.piutang-product.index')">
Dashboard 
</x-mail::button>
@else
<x-mail::button :url="route('master-data.piutang.index')">
Dashboard 
</x-mail::button>
@endif

@else
<x-mail::button :url="route('transactions.piutang.customer.index')">
Dashboard 
</x-mail::button>
@endif

## Note

You can refuse to accept a shipment if the outer packaging is tampered/damaged//torn/pressed/disturbed. Please mention the same reason on the POD slip. For questions, you can reach out to us at <br> 
<x-mail::textlink url="mailto:support@shiprocket.in">support@shiprocket.in</x-mail::textlink>

## Best Regards,
### Team ShipRocket

This is a system generated message.
Do not reply.
</x-slot:subcopy>

<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>


</x-mail::message>
