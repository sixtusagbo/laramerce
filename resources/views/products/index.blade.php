@extends('layouts.base')

@section('content')
    <x-success />
    <x-error-messages />
    <div class="flex items-center justify-between px-10">
        <div class="flex flex-col">
            <x-title color="text-rose-500">
                {{ __('Welcome to our store, :NAME', ['name' => $currentUserName]) }}
            </x-title>
            <p>{{ $currentDateTime }}</p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('products.create') }}" class="bg-green-400 rounded-lg p-4 mt-4">Create Product</a>
            <a href="{{ route('cart.index') }}" class="bg-purple-400 rounded-lg p-4 mt-4">Go to Cart</a>
            @if (Route::has('login'))
                <nav class="flex flex-1 justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-green-400 rounded-lg p-4 mt-4">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-green-400 rounded-lg p-4 mt-4">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-red-400 rounded-lg p-4 mt-4">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

            {{-- Select for languages --}}
            <form action="{{ route('locale.set') }}" method="GET">
                @csrf
                <select name="locale" id="locale" onchange="this.form.submit()"
                    class="bg-yellow-400 border border-gray-300 rounded p-4 mt-4">
                    <option value="en" @if (app()->getLocale() == 'en') selected @endif>English 🏴󠁧󠁢󠁥󠁮󠁧󠁿</option>
                    <option value="fr" @if (app()->getLocale() == 'fr') selected @endif>French 🇫🇷</option>
                    <option value="zh" @if (app()->getLocale() == 'zh') selected @endif>China 🇨🇳</option>
                </select>
            </form>
        </div>
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
                    <p class="text-sm text-gray-600">Stock: {{ $product->stock }}</p>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4"
                            onclick="showModal({{ $product->id }})">
                            Add to Cart
                        </button>
                        <a href="{{ route('products.show', $product->id) }}"
                            class="bg-green-400 rounded-lg p-2 mt-4">View</a>
                    </div>
                </div>

                <!-- Add to cart modal -->
                <div class="fixed z-10 inset-0 overflow-y-auto hidden flex items-center justify-center"
                    id="modal-{{ $product->id }}">
                    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-800">Add {{ $product->name }} to Cart</h3>
                                <button type="button" onclick="hideModal({{ $product->id }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full">
                                    &times;
                                </button>
                            </div>
                            <form action="{{ route('cart.store') }}" method="POST"
                                id="add-to-cart-form-{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                                <input type="number" name="quantity" value="1"
                                    class="w-full p-2 border border-gray-300 rounded mt-2">

                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 mb-4 w-full">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showModal(productId) {
            // Hide all other modals
            var modals = document.querySelectorAll('[id^="modal-"]');
            modals.forEach(function(modal) {
                modal.classList.add('hidden');
            });

            // Show the current modal
            document.getElementById(`modal-${productId}`).classList.remove('hidden');
        }

        function hideModal(productId) {
            document.getElementById(`modal-${productId}`).classList.add('hidden');
        }
    </script>
@endsection
