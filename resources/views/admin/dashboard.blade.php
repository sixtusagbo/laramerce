<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = Auth::user();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($user->unreadNotifications->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{-- Header --}}
                        <div class="flex justify-between items-center mb-6">
                            <p>{{ __('Notifications:') }}</p>
                            <div class="flex gap-8">
                                <a href="{{ route('notifications.mark_as_read') }}" class="text-green-500">Mark all as
                                    read</a>
                                <a href="#" class="text-red-400">Delete all</a>
                            </div>
                        </div>

                        {{-- Body --}}
                        @foreach ($user->unreadNotifications as $notification)
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                                role="alert">
                                <div class="flex justify-between items-center">
                                    <p>
                                        <strong class="font-bold">New Product:</strong>
                                        <span class="block sm:inline">
                                            {{ $notification->data['name'] }} was added ·
                                            {{ \Carbon\Carbon::create($notification->data['created_at'])->diffForHumans() }}
                                        </span>
                                    </p>

                                    <a href="{{ route('notifications.mark_as_read', ['id' => $notification->id]) }}"
                                        class="text-orange-500">Mark as read</a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in as admin!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
