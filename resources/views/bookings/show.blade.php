@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">Booking Details</h1>
            <a href="{{ route('bookings.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Back to Bookings
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-gray-600 font-semibold">Booking ID:</p>
                <p class="text-gray-800">{{ $booking->id }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Status:</p>
                <p class="text-gray-800 capitalize">{{ $booking->status }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Booked by:</p>
                <p class="text-gray-800">{{ $booking->user->name }} ({{ $booking->user->email }})</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Field:</p>
                <p class="text-gray-800">{{ $booking->field->name }} ({{ $booking->field->type }})</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Booking Date:</p>
                <p class="text-gray-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Time Slot:</p>
                <p class="text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Total Price:</p>
                <p class="text-gray-800">€{{ number_format($booking->total_price, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Booked At:</p>
                <p class="text-gray-800">{{ $booking->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="mt-8 flex space-x-3">
            {{-- We will use a BookingPolicy to control these actions --}}
            {{-- @can('update', $booking) --}}
            <a href="{{ route('bookings.edit', $booking->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit Booking
            </a>
            {{-- @endcan --}}

            {{-- @can('delete', $booking) --}}
            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Delete Booking
                </button>
            </form>
            {{-- @endcan --}}
        </div>

    </div>
</div>
@endsection
