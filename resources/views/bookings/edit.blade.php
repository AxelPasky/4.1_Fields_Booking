<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative\" role="alert">
                            <strong class="font-bold">{{ __('Whoops! Something went wrong.') }}</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Field -->
                        <div class="mt-4">
                            <label for="field_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300\">{{ __('Field') }}</label>
                            <select name="field_id" id="field_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\" required>
                                <option value="">{{ __('Select a Field') }}</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}" {{ old('field_id', $booking->field_id) == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }} ({{ $field->type }}) - €{{ number_format($field->price_per_hour, 2) }}/hr
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Booking Date -->
                        <div class="mt-4">
                            <label for="booking_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Booking Date') }}</label>
                            <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <!-- Start Time -->
                        <div class="mt-4">
                            <label for="start_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Start Time') }}</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <!-- End Time -->
                        <div class="mt-4">
                            <label for="end_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('End Time') }}</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('bookings.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Cancel') }}
                            </a>

                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Update Booking') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
