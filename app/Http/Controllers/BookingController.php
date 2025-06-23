<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Notifications\BookingCancelledForAdminNotification;
use App\Notifications\BookingCancelledForUserNotification;
use App\Notifications\BookingCreatedForAdminNotification;
use App\Notifications\BookingCreatedForUserNotification;
use App\Notifications\BookingUpdatedForAdminNotification;
use App\Notifications\BookingUpdatedForUserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Booking::class);

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
    public function store(StoreBookingRequest $request)
    {
        $validatedData = $request->validated();
        $bookingData = $this->prepareBookingData($validatedData);

        // Aggiungiamo i dati specifici per la creazione
        $bookingData['user_id'] = Auth::id();
        $bookingData['status'] = 'confirmed';

        $booking = Booking::create($bookingData);

        // Notification
        $admins = User::where('is_admin', true)->get();
        Notification::send($admins, new BookingCreatedForAdminNotification($booking));
        Auth::user()->notify(new BookingCreatedForUserNotification($booking));

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['user', 'field']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        $fields = Field::orderBy('name')->get(); // Mostra tutti i campi, anche non disponibili, per la modifica
        return view('bookings.edit', compact('booking', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBookingRequest $request, Booking $booking)
    {
        $validatedData = $request->validated();
        $bookingData = $this->prepareBookingData($validatedData);

        $booking->update($bookingData);

        // Notification
        if (Auth::user()->is_admin) {
            if ($booking->user_id !== Auth::id()) {
                $booking->user->notify(new BookingUpdatedForUserNotification($booking));
            }
        } else {
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new BookingUpdatedForAdminNotification($booking));
        }

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        // Notification
        if (Auth::user()->is_admin) {
            if ($booking->user_id !== Auth::id()) {
                $booking->user->notify(new BookingCancelledForUserNotification($booking));
            }
        } else {
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new BookingCancelledForAdminNotification($booking));
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }

    /**
     * @param array $validatedData
     * @return array
     */
    
    private function prepareBookingData(array $validatedData): array
    {
        $startDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['end_time']);
        $field = Field::findOrFail($validatedData['field_id']);
        $durationInHours = $startDateTime->diffInMinutes($endDateTime) / 60;

        return [
            'field_id' => $validatedData['field_id'],
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'total_price' => $field->price_per_hour * $durationInHours,
        ];
    }
}
