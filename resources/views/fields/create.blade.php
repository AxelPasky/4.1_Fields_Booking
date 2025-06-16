<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Field') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Mostra errori di validazione -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('fields.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Field Name') }}</label>
                            <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="name" value="{{ old('name') }}" required autofocus />
                        </div>

                        <!-- Type -->
                        <div class="mt-4">
                            <label for="type" class="block font-medium text-sm text-gray-700">{{ __('Field Type') }}</label>
                            <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="tennis" {{ old('type') == 'tennis' ? 'selected' : '' }}>{{ __('Tennis') }}</option>
                                <option value="padel" {{ old('type') == 'padel' ? 'selected' : '' }}>{{ __('Padel') }}</option>
                                <option value="football" {{ old('type') == 'football' ? 'selected' : '' }}>{{ __('Football') }}</option>
                                <option value="basket" {{ old('type') == 'basket' ? 'selected' : '' }}>{{ __('Basket') }}</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">{{ __('Description (optional)') }}</label>
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        </div>

                        <!-- Image Path (Placeholder) -->
                        <div class="mt-4">
                            <label for="image_path" class="block font-medium text-sm text-gray-700">{{ __('Image Path (optional, e.g., /images/field1.jpg)') }}</label>
                            <input id="image_path" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="image_path" value="{{ old('image_path') }}" />
                            <p class="mt-2 text-sm text-gray-500">Note: Actual image upload handling will be implemented later.</p>
                        </div>

                        <!-- price_per_hour -->
                        <div class="mt-4">
                            <label for="price_per_hour" class="block font-medium text-sm text-gray-700">{{ __('Price per Hour (€)') }}</label>
                            <input id="price_per_hour" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="price_per_hour" value="{{ old('price_per_hour') }}" required step="0.01" min="0" />
                        </div>

                        <!-- Is Available -->
                        <div class="block mt-4">
                            <label for="is_available" class="inline-flex items-center">
                                <input id="is_available" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="is_available" value="1" {{ old('is_available') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Available') }}</span>
                            </label>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('fields.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Save Field') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>