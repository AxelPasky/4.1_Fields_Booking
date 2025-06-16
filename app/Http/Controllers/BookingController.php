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
            // We will add more complex validation for booking conflicts later
        ], [
            'field_id.exists' => 'The selected field is not available or does not exist.',
            'booking_date.after_or_equal' => 'The booking date cannot be in the past.',
            'end_time.after' => 'The end time must be after the start time.'
        ]);

        // Basic check for booking conflicts (can be expanded)
        $conflict = Booking::where('field_id', $validatedData['field_id'])
            ->where('booking_date', $validatedData['booking_date'])
            ->where(function ($query) use ($validatedData) {
                $query->where(function ($q) use ($validatedData) {
                    // New booking starts during an existing booking
                    $q->where('start_time', '<', $validatedData['end_time'])
                      ->where('end_time', ' > ', $validatedData['start_time']);
                });
            })
            ->where('status', '!=', 'cancelled') // Ignore cancelled bookings
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withErrors(['conflict' => 'The selected time slot for this field is already booked or overlaps with an existing booking.'])
                ->withInput();
        }

        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->field_id = $validatedData['field_id'];
        $booking->booking_date = $validatedData['booking_date'];
        $booking->start_time = $validatedData['start_time'];
        $booking->end_time = $validatedData['end_time'];
        $booking->status = 'confirmed'; // Default status

        // Calculate total_price (simple example, can be refined)
        $field = Field::findOrFail($validatedData['field_id']);
        $startTime = Carbon::parse($validatedData['start_time']);
        $endTime = Carbon::parse($validatedData['end_time']);
        $durationInHours = $endTime->diffInMinutes($startTime) / 60;
        
        if ($durationInHours <= 0) { // Should be caught by 'after:start_time' but as a safeguard
             return redirect()->back()
                ->withErrors(['duration' => 'The booking duration must be positive.'])
                ->withInput();
        }
        $booking->total_price = $field->price_per_hour * $durationInHours;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
