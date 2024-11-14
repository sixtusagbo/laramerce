<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your cart') }}

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('products.index') }}" class="bg-green-400 rounded-lg p-2 mt-2">Continue
                    Shopping</a>
                <form action="{{ route('cart.clear', ['cart' => $cart]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white rounded-lg p-2 mt-2">Clear
                        cart</button>
                </form>
            </div>
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($cart === null)
                    <div class="bg-white shadow-md rounded-lg p-4 flex items-center">
                        <h2 class="text-lg font-medium text-gray-800">No products found</h2>
                    </div>
                @else
                    <div class="container mx-auto px-4 py-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @if ($cart)
                                @forelse ($cart->products as $product)
                                    <div class="bg-white shadow-md rounded-lg p-4">
                                        <h2 class="text-lg font-medium text-gray-800">{{ $product->name }}</h2>
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-32 object-cover mt-2">
                                        <p class="text-sm text-gray-500">{{ $product->description }}</p>
                                        <p class="text-sm text-gray-500">Unit Price: &#8358;{{ $product->price }}
                                        </p>
                                        <p class="text-sm text-gray-500">Quantity: {{ $product->pivot->quantity }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Total Price: &#8358;{{ $product->total_price }}
                                        </p>

                                        <form
                                            action="{{ route('cart.remove', ['cart' => $cart, 'product' => $product]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white rounded-lg p-2 mt-2">Remove from
                                                Cart</button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="bg-white shadow-md rounded-lg p-4">
                                        <h2 class="text-lg font-medium text-gray-800">
                                            Your cart is empty
                                        </h2>
                                    </div>
                                @endforelse
                            @else
                                <div class="bg-white shadow-md rounded-lg p-4">
                                    <h2 class="text-lg font-medium text-gray-800">No products found</h2>
                                </div>
                            @endif
                        </div>

                        <div class="bg-white shadow-md rounded-lg p-4 mt-5 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-800">
                                Total: ${{ $cart->total_price }}
                            </h2>
                            @if (!$cart->checked_out)
                                <button onclick="initiatePayment()"
                                    class="bg-blue-500 text-white rounded-lg p-2 mt-2">Checkout</button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-slot name="scripts">

        @if ($cart !== null)
            <script src="https://js.paystack.co/v2/inline.js"></script>

            <script>
                async function initiatePayment() {
                    const popup = new PaystackPop();

                    await popup.checkout({
                        key: "{{ config('app.paystack.public_key') }}",
                        email: "{{ $user->email }}",
                        amount: {{ $cart->total_price * 100 }},
                        firstName: "{{ $user->name }}",
                        onLoad: (response) => {
                            console.log("onLoad: ", response);
                        },
                        onSuccess: (transaction) => {
                            console.log("onSuccess: ");
                            console.log(transaction);
                            window.location.href = "/payment/verify?reference=" + transaction.reference +
                                "&cart_id=" + "{{ $cart->id }}";
                        },
                        onCancel: () => {
                            console.log("Payment cancelled");
                        },
                        onError: (error) => {
                            console.log("Error: ", error.message);
                        }
                    });
                }
            </script>
        @endif
    </x-slot>
</x-app-layout>
