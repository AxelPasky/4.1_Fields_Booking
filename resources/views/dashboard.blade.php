<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">What would you like to do today?</p>

                    {{-- Pulsanti di azione per tutti gli utenti --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold">Quick Actions</h4>
                        <div class="mt-4 flex flex-wrap gap-4">
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View My Bookings
                            </a>
                            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Book a New Field
                            </a>
                        </div>
                    </div>

                    {{-- Sezione visibile solo agli amministratori --}}
                    @can('create', App\Models\Field::class)
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-red-500">Admin Panel</h4>
                            <div class="mt-4 flex flex-wrap gap-4">
                                <a href="{{ route('fields.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Manage Fields
                                </a>
                                {{-- Potremmo aggiungere qui un link per gestire tutti gli utenti o tutte le prenotazioni --}}
                            </div>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
