<!-- resources/views/fields/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Field:') }} {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('fields.update', $field) }}" enctype="multipart/form-data" >
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Field Name') }}</label>
                            <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="name" value="{{ old('name', $field->name) }}" required autofocus />
                        </div>

                        <!-- Type -->
                        <div class="mt-4">
                            <label for="type" class="block font-medium text-sm text-gray-700">{{ __('Field Type') }}</label>
                            <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="tennis" {{ old('type', $field->type) == 'tennis' ? 'selected' : '' }}>{{ __('Tennis') }}</option>
                                <option value="padel" {{ old('type', $field->type) == 'padel' ? 'selected' : '' }}>{{ __('Padel') }}</option>
                                <option value="football" {{ old('type', $field->type) == 'football' ? 'selected' : '' }}>{{ __('Football') }}</option>
                                <option value="basket" {{ old('type', $field->type) == 'basket' ? 'selected' : '' }}>{{ __('Basket') }}</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">{{ __('Description (optional)') }}</label>
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $field->description) }}</textarea>
                        </div>

                        <!-- Image -->
                        <div class="mt-4">
                            <label for="image" class="block font-medium text-sm text-gray-700">{{ __('New Field Image (optional)') }}</label>
                            <input type="file" name="image" id="image" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            @if ($field->image)
                                <p class="mt-2 text-sm text-gray-500">Current image: <img src="{{ asset('storage/' . $field->image) }}" alt="Current image" class="h-16 w-16 object-cover inline-block ml-2"></p>
                            @endif
                        </div>

                        <!-- Hourly Rate -->
                        <div class="mt-4">
                            <label for="price_per_hour" class="block font-medium text-sm text-gray-700">{{ __('Price per Hour (€)') }}</label>
                            <input id="price_per_hour" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="price_per_hour" value="{{ old('price_per_hour', $field->price_per_hour) }}" required step="0.01" min="0" />
                        </div>

                        <!-- Is Available -->
                        <div class="block mt-4">
                            <label for="is_available" class="inline-flex items-center">
                                <input id="is_available" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="is_available" value="1" {{ old('is_available', $field->is_available) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Available') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('fields.show', $field) }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border-4 border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 p-4">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

