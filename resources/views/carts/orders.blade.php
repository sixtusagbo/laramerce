<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table
                        class="border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800 text-sm shadow-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th
                                    class="w-[5%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    ID
                                </th>
                                <th
                                    class="w-[10%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Created
                                </th>
                                <th
                                    class="w-[40%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Products
                                </th>
                                <th
                                    class="w-[15%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Price
                                </th>
                                <th
                                    class="w-[15%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Checked out?
                                </th>
                                <th
                                    class="w-[15%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Checked out
                                </th>
                                <th
                                    class="w-[15%] border border-slate-300 dark:border-slate-600 font-semibold p-2 text-slate-900 dark:text-slate-200 text-left">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($carts as $cart)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        #{{ $cart->id }}
                                    </td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        {{ $cart->created_at->diffForHumans() }}
                                    </td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        {{ $cart->products->pluck('name')->join(', ') }}. <br>
                                        <p class="font-bold">
                                            No. of products: {{ $cart->products->count() }}</p> <br>
                                    </td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        &#8358;{{ $cart->total_price }}</td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        @if ($cart->checked_out)
                                            {{-- Green yes label --}}
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Yes
                                            </span>
                                        @else
                                            {{-- Red no label --}}
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        {{ $cart->checked_out_at->diffForHumans() }}</td>
                                    <td
                                        class="border border-slate-300 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('cart.index', ['id' => $cart->id]) }}"
                                                class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                                                View
                                            </a>
                                            <form action="{{ route('cart.destroy', $cart) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
                                                    onclick="return confirm('Are you sure you want to delete this cart?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400"
                                        colspan="7">
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
