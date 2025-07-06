@props([
    'url',
    'color' => 'blue-500',
])

<span>
    <a href="{{ $url }}" class="text-{{ $color }}" target="_blank" rel="noopener">
        {{ $slot }}
    </a>
</span>