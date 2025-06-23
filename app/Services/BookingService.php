<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Notifications\BookingCreatedForAdminNotification;
use App\Notifications\BookingCreatedForUserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class BookingService
{
    /**
     
     *
     * @param array $validatedData 
     * @param User $user 
     * @return Booking 
     */
    public function createBooking(array $validatedData, User $user): Booking
    {
        $startDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['end_time']);
        $field = Field::findOrFail($validatedData['field_id']);
        $durationInHours = $startDateTime->diffInMinutes($endDateTime) / 60;

        $booking = Booking::create([
            'user_id' => $user->id,
            'field_id' => $validatedData['field_id'],
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'status' => 'confirmed',
            'total_price' => $field->price_per_hour * $durationInHours,
        ]);

        $admins = User::where('is_admin', true)->get();
        Notification::send($admins, new BookingCreatedForAdminNotification($booking));
        $user->notify(new BookingCreatedForUserNotification($booking));

        return $booking;
    }
}