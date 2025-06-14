<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Aggiungi Nuovo Campo Sportivo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Mostra errori di validazione --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ci sono stati alcuni problemi con i dati inseriti.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('fields.store') }}">
                        @csrf {{-- Token CSRF per la sicurezza --}}

                        {{-- Nome Campo --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Campo</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                        </div>

                        {{-- Tipo Campo --}}
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Campo</label>
                            <select name="type" id="type" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                                <option value="">Seleziona un tipo</option>
                                <option value="tennis" {{ old('type') == 'tennis' ? 'selected' : '' }}>Tennis</option>
                                <option value="padel" {{ old('type') == 'padel' ? 'selected' : '' }}>Padel</option>
                                <option value="calcio" {{ old('type') == 'calcio' ? 'selected' : '' }}>Calcio</option>
                                <option value="basket" {{ old('type') == 'basket' ? 'selected' : '' }}>Basket</option>
                                {{-- Aggiungi altri tipi se necessario --}}
                            </select>
                        </div>

                        {{-- Località --}}
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Località</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                        </div>

                        {{-- Prezzo per Ora --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prezzo per Ora (€)</label>
                            <input type="number" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" required step="0.01" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                        </div>
                        
                        {{-- Immagine (per ora solo un input testuale per il percorso) --}}
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Percorso Immagine (es. /images/campo1.jpg)</label>
                            <input type="text" name="image" id="image" value="{{ old('image') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('fields.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                Annulla
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Salva Campo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>