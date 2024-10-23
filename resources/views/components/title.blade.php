@props(['color'])

<h1 class="text-4xl font-bold text-center pt-5 {{ $color }}">
    {{ $slot }}
</h1>
