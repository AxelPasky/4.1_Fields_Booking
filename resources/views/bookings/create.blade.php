<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make a New Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.store') }}">
                        @csrf

                        <!-- Field Selection -->
                        <div class="mt-4">
                            <label for="field_id" class="block font-medium text-sm text-gray-700">{{ __('Select Field') }}</label>
                            <select id="field_id" name="field_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">{{ __('-- Please choose a field --') }}</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }} ({{ $field->type }}) - &euro;{{ number_format($field->price_per_hour, 2) }}/hr
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Booking Date -->
                        <div class="mt-4">
                            <label for="booking_date" class="block font-medium text-sm text-gray-700">{{ __('Booking Date') }}</label>
                            <input id="booking_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="booking_date" value="{{ old('booking_date') }}" required />
                        </div>

                        <!-- Start Time -->
                        <div class="mt-4">
                            <label for="start_time" class="block font-medium text-sm text-gray-700">{{ __('Start Time') }}</label>
                            <input id="start_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start_time" value="{{ old('start_time') }}" required />
                        </div>

                        <!-- End Time -->
                        <div class="mt-4">
                            <label for="end_time" class="block font-medium text-sm text-gray-700">{{ __('End Time') }}</label>
                            <input id="end_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="end_time" value="{{ old('end_time') }}" required />
                        </div>

                        <!-- Additional Notes (Optional) -->
                        {{-- <div class="mt-4">
                            <label for="notes" class="block font-medium text-sm text-gray-700">{{ __('Additional Notes (optional)') }}</label>
                            <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                        </div> --}}

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('bookings.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Submit Booking Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
