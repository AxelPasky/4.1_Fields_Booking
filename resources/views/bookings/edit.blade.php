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

                    {{-- Mostra un riepilogo di tutti gli errori --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
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
                            <x-input-label for="field_id" :value="__('Field')" />
                            <select name="field_id" id="field_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" {{ old('field_id', $booking->field_id) == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }} (€{{ number_format($field->price_per_hour, 2) }}/hr)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('field_id')" class="mt-2" />
                        </div>

                        <!-- Booking Date -->
                        <div class="mt-4">
                            <x-input-label for="booking_date" :value="__('Booking Date')" />
                            <x-text-input id="booking_date" class="block mt-1 w-full" type="date" name="booking_date" :value="old('booking_date', $booking->start_time->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('booking_date')" class="mt-2" />
                        </div>

                        <!-- Start Time -->
                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <select id="start_time" name="start_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @for ($h = 0; $h < 24; $h++)
                                    @for ($m = 0; $m < 60; $m += 30)
                                        @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                        <option value="{{ $time }}" {{ old('start_time', $booking->start_time->format('H:i')) == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>
                                    @endfor
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <!-- End Time -->
                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <select id="end_time" name="end_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @for ($h = 0; $h < 24; $h++)
                                    @for ($m = 0; $m < 60; $m += 30)
                                        @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                        <option value="{{ $time }}" {{ old('end_time', $booking->end_time->format('H:i')) == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>
                                    @endfor
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Booking') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
