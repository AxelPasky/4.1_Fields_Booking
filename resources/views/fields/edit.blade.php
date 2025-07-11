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

                        <!-- Field Type -->
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Field Type')" />
                            <select name="type" id="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @php
                                    $types = ['tennis', 'padel', 'football', 'basket'];
                                @endphp
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('type', $field->type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
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

                        <!-- Price Per Hour -->
                        <div class="mt-4">
                            <x-input-label for="price_per_hour" :value="__('Price per Hour (€)')" />
                            <x-text-input id="price_per_hour" class="block mt-1 w-full" type="number" name="price_per_hour" :value="old('price_per_hour', $field->price_per_hour)" required min="0" step="0.01" />
                            <x-input-error :messages="$errors->get('price_per_hour')" class="mt-2" />
                        </div>

                        <!-- Is Available Checkbox -->
                        <div class="block mt-4">
                            <label for="is_available" class="inline-flex items-center">
                                <input id="is_available" type="checkbox" class="rounded border-gray-300 text-brand-green-600 shadow-sm focus:ring-brand-green-500" name="is_available" value="1" {{ old('is_available', $field->is_available) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Available for booking') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('fields.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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

