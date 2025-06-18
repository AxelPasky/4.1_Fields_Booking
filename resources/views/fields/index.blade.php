<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fields') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- NUOVO: Blocco per i messaggi di sessione -->
            <div class="mb-4">
                <x-auth-session-status class="mb-2" :status="session('success')" />
                @if (session('error'))
                    <div class="font-medium text-sm text-red-600 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="flex justify-end mb-4">
                {{-- Mostra il pulsante "Create" solo agli utenti che possono creare campi (admin) --}}
                @can('create', App\Models\Field::class)
                    <a href="{{ route('fields.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Create New Field') }}
                    </a>
                @endcan
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price/Hour</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($fields as $field)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $field->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($field->price_per_hour, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $field->is_available ? 'Yes' : 'No' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('fields.show', $field->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View</a>
                                        {{-- Mostra "Edit" solo se l'utente può aggiornare --}}
                                        @can('update', $field)
                                            <a href="{{ route('fields.edit', $field->id) }}" class="ml-4 text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">Edit</a>
                                        @endcan
                                        {{-- Mostra "Delete" solo se l'utente può eliminare --}}
                                        @can('delete', $field)
                                            <form class="inline-block ml-4" action="{{ route('fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No fields found.
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