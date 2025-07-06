<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @stack('styles')
</head>

<body class="">
    {{ $slot }}
    
    @stack('scripts')
    @fluxScripts
</body>

</html>
