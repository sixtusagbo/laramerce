<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'larafoo') }} | Products</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="font-sans antialiased">
    <h1 class="text-4xl font-bold text-center pt-5">Welcome to our store</h1>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-medium text-gray-800 mb-4">Products</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover object-center mb-4">
                    <h2 class="text-lg font-medium text-gray-800">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $product->description }}</p>
                    <p class="text-lg font-medium text-gray-800 mt-2">${{ $product->price }}</p>

                    <div class="flex items-center justify-end">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Add to
                            Cart</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
