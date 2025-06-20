<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Notifications\BookingCancelledForAdminNotification;
use App\Notifications\BookingCancelledForUserNotification;
use App\Notifications\BookingCreatedForUserNotification;
use App\Notifications\BookingCreatedForAdminNotification;
use App\Notifications\BookingUpdatedForUserNotification;
use App\Notifications\BookingUpdatedForAdminNotification;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Booking::class); // Decommenta questa riga

        if (Auth::user()->is_admin) {
            $bookings = Booking::with(['user', 'field'])->latest()->paginate(10);
        } else {
            $bookings = Booking::where('user_id', Auth::id())
                                 ->with(['field'])
                                 ->latest()
                                 ->paginate(10);
        }
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Booking::class);

        $fields = Field::where('is_available', true)->orderBy('name')->get();
        return view('bookings.create', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Booking::class);

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
        $booking->status = 'confirmed'; // Or 'pending' if you have a confirmation step

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

        $field = Field::findOrFail($validatedData['field_id']);
        $booking->total_price = $field->price_per_hour * $durationInHours;
        // $booking->notes = $request->input('notes'); // If you add a notes field

        $booking->save();

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        // Eager load relationships if not already loaded by route model binding, or if needed for the view
        $booking->load(['user', 'field']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        $fields = Field::where('is_available', true)->orderBy('name')->get();
        return view('bookings.edit', compact('booking', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validatedData = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $startDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['end_time']);

        // Check for overlapping bookings, excluding the current one
        $overlappingBookings = Booking::where('field_id', $validatedData['field_id'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($startDateTime, $endDateTime) {
                $query->where('start_time', '<', $endDateTime)
                      ->where('end_time', '>', $startDateTime);
            })->exists();

        if ($overlappingBookings) {
            return back()->withErrors(['booking_date' => 'The selected time slot is already booked.'])->withInput();
        }

        $field = Field::findOrFail($validatedData['field_id']);
        $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
        $totalPrice = $durationInHours * $field->price_per_hour;

        $booking->update([
            'field_id' => $validatedData['field_id'],
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'total_price' => $totalPrice,
        ]);

        // --- INIZIO BLOCCO NOTIFICHE ---
        // Controlla chi sta modificando e invia la notifica appropriata
        if (Auth::user()->is_admin) {
            // L'admin sta modificando, avvisa l'utente proprietario della prenotazione
            $userToNotify = $booking->user;
            // Assicurati che l'admin non stia modificando una propria prenotazione
            if ($userToNotify->id !== Auth::id()) {
                $userToNotify->notify(new BookingUpdatedForUserNotification($booking));
            }
        } else {
            // L'utente sta modificando, avvisa tutti gli admin
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new BookingUpdatedForAdminNotification($booking));
        }
        // --- FINE BLOCCO NOTIFICHE ---

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if (Auth::user()->is_admin) {
            // L'admin sta cancellando, avvisa l'utente
            $userToNotify = $booking->user;
            if ($userToNotify->id !== Auth::id()) { // Non inviare a te stesso se sei l'utente
                $userToNotify->notify(new BookingCancelledForUserNotification($booking));
            }
        } else {
            // L'utente sta cancellando, avvisa tutti gli admin
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new BookingCancelledForAdminNotification($booking));
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
