<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Customers -->
                <div class="bg-white p-6 shadow rounded-lg text-center">
                    <h3 class="text-gray-500 font-semibold">Total Customers</h3>
                    <p class="text-3xl font-bold">{{ $totalCustomers }}</p>
                </div>

                <!-- Total Messages -->
                <div class="bg-white p-6 shadow rounded-lg text-center">
                    <h3 class="text-gray-500 font-semibold">Total Messages Sent</h3>
                    <p class="text-3xl font-bold">{{ $totalMessages }}</p>
                </div>

                <!-- Total Calls -->
                <div class="bg-white p-6 shadow rounded-lg text-center">
                    <h3 class="text-gray-500 font-semibold">Total Calls Made</h3>
                    <p class="text-3xl font-bold">{{ $totalCalls }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
