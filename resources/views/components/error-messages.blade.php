<div class="flex justify-center items-center bg-red-100">
    @if (session('error'))
        <x-error>
            {{ session('error') }}
        </x-error>
    @endif

    @if (session('errors'))
        @foreach (session('errors') as $error)
            <x-error>
                {{ $error }}
            </x-error>
        @endforeach
    @endif
</div>
