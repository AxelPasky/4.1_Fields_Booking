<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For now, admins see all bookings, users see their own.
        // We will refine this later with proper authorization.
        if (Auth::user()->is_admin) {
            $bookings = Booking::with(['user', 'field'])->latest()->paginate(10);
        } else {
            $bookings = Booking::where('user_id', Auth::id())
                                ->with(['user', 'field'])->latest()->paginate(10);
        }
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = Field::where('is_available', true)->orderBy('name')->get();
        return view('bookings.create', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'field_id' => [
                'required',
                Rule::exists('fields', 'id')->where(function ($query) {
                    return $query->where('is_available', true);
                }),
            ],
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'field_id.exists' => 'The selected field is not available or does not exist.',
            'booking_date.after_or_equal' => 'The booking date cannot be in the past.',
            'end_time.after' => 'The end time must be after the start time.'
        ]);

        $bookingStartDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $bookingEndDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['end_time']);

        $conflict = Booking::where('field_id', $validatedData['field_id'])
            ->where(function ($query) use ($bookingStartDateTime, $bookingEndDateTime) {
                $query->where(function ($q) use ($bookingStartDateTime, $bookingEndDateTime) {
                    $q->where('start_time', '<', $bookingEndDateTime)
                      ->where('end_time', '>', $bookingStartDateTime);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withErrors(['conflict' => 'The selected time slot for this field is already booked or overlaps with an existing booking.'])
                ->withInput();
        }

        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->field_id = $validatedData['field_id'];
        $booking->start_time = $bookingStartDateTime;
        $booking->end_time = $bookingEndDateTime;
        $booking->status = 'confirmed';

        $diffMinutesRaw = $bookingStartDateTime->diffInMinutes($bookingEndDateTime, false);
        $durationInMinutes = abs($diffMinutesRaw);
        $durationInHours = $durationInMinutes / 60;

        if ($durationInHours <= 0) {
             if (!($bookingStartDateTime->eq($bookingEndDateTime) && $durationInHours == 0)) {
                return redirect()->back()
                    ->withErrors(['duration' => 'The booking duration must be positive. Please ensure the end time is after the start time.'])
                    ->withInput();
             }
        }

        // Define $field before using it for total_price
        $field = Field::findOrFail($validatedData['field_id']);
        $booking->total_price = $field->price_per_hour * $durationInHours;
        // $booking->notes = $request->input('notes');

        $booking->save();

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Authorize: only the user who made the booking or an admin can view it.
        if (Auth::id() !== $booking->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        // Eager load related models if you haven't already in the route model binding
        // or if you need them specifically here.
        $booking->load(['user', 'field']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Authorize: only the user who made the booking or an admin can edit it.
        if (Auth::id() !== $booking->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        $fields = Field::where('is_available', true)->orderBy('name')->get();
        return view('bookings.edit', compact('booking', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        if (!auth()->user()->is_admin && auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'field_id' => [
                'required',
                'exists:fields,id'
            ],
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'booking_date.after_or_equal' => 'The booking date must be today or a future date.',
            'end_time.after' => 'The end time must be after the start time.'
        ]);

        $bookingStartDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $bookingEndDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['end_time']);

        $conflict = Booking::where('field_id', $validatedData['field_id'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($bookingStartDateTime, $bookingEndDateTime) {
                $query->where(function ($q) use ($bookingStartDateTime, $bookingEndDateTime) {
                    $q->where('start_time', '<', $bookingEndDateTime)
                      ->where('end_time', '>', $bookingStartDateTime);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withErrors(['conflict' => 'The selected time slot is already booked.'])
                ->withInput();
        }

        $booking->field_id = $validatedData['field_id'];
        $booking->start_time = $bookingStartDateTime;
        $booking->end_time = $bookingEndDateTime;

        $diffMinutesRaw = $bookingStartDateTime->diffInMinutes($bookingEndDateTime, false);
        $durationInMinutes = abs($diffMinutesRaw);
        $durationInHours = $durationInMinutes / 60;
        
        if ($durationInHours <= 0) {
             if (!($bookingStartDateTime->eq($bookingEndDateTime) && $durationInHours == 0)) {
                return redirect()->back()
                    ->withErrors(['duration' => 'The booking duration must be positive. Please ensure the end time is after the start time.'])
                    ->withInput();
             }
        }

        // Define $field before using it for total_price
        $field = Field::findOrFail($validatedData['field_id']);
        $booking->total_price = $field->price_per_hour * $durationInHours;
        // $booking->notes = $request->input('notes');

        $booking->save();

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        // Check if user is authorized to delete this booking
        if (!auth()->user()->is_admin && auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the booking is in the past using the start_time (which is now dateTime)
        if (Carbon::parse($booking->start_time)->isPast()) {
            return redirect()->back()
                ->with('error', 'Cannot delete past bookings.');
        }

        // Soft delete the booking by setting status to 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
