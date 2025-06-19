<!-- resources/views/fields/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Field Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Mostra l'immagine se esiste (con la colonna corretta) --}}
                    @if($field->image)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="w-full h-64 object-cover rounded-lg shadow-md">
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $field->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Price per Hour</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">€{{ number_format($field->price_per_hour, 2) }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $field->description ?? 'No description provided.' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $field->is_available ? 'Available' : 'Not Available' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center space-x-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('fields.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Back to List') }}
                        </a>
                        @can('update', $field)
                            <a href="{{ route('fields.edit', $field->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Edit') }}
                            </a>
                        @endcan
                        @can('delete', $field)
                            <form method="POST" action="{{ route('fields.destroy', $field->id) }}" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
