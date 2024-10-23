@extends('layouts.base')

@section('content')
    <x-success />
    <div class="flex items-center justify-between">
        <x-title color="text-rose-500">Welcome to our store</x-title>
        <a href="{{ route('products.create') }}" class="bg-green-400 rounded-lg p-4">Create Product</a>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover object-center mb-4">
                    <h2 class="text-lg font-medium text-gray-800">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $product->description }}</p>
                    <p class="text-lg font-medium text-gray-800 mt-2">&#8358;{{ $product->price }}</p>

                    <div class="flex items-center justify-end">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Add to
                            Cart</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
