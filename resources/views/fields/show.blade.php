<!-- resources/views/fields/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Field Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ $field->name }}</h3>
                    <p class="mt-1 text-sm text-gray-600"><strong>{{ __('Type:') }}</strong> {{ $field->type }}</p>
                    <p class="mt-1 text-sm text-gray-600"><strong>{{ __('Description:') }}</strong> {{ $field->description ?: 'N/A' }}</p>
                    <p class="mt-1 text-sm text-gray-600"><strong>{{ __('Image (path):') }}</strong> {{ $field->image_path ?: 'N/A' }}</p>
                    <p class="mt-1 text-sm text-gray-600"><strong>{{ __('Price per Hour:') }}</strong> €{{ number_format($field->price_per_hour, 2, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-gray-600"><strong>{{ __('Available:') }}</strong> {{ $field->is_available ? __('Yes') : __('No') }}</p>

                    <div class="mt-6">
                        <a href="{{ route('fields.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Back to list') }}
                        </a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('fields.edit', $field) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('fields.destroy', $field) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('{{ __('Are you sure you want to delete this field?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
