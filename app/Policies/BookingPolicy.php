<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Carbon\Carbon; // Import Carbon

class BookingPolicy
{
    /**
     * Perform pre-authorization checks.
     *
     * Questo metodo viene eseguito prima di ogni altro metodo nella policy.
     * Se restituisce true o false, quella decisione viene presa immediatamente.
     * Se restituisce null, si procede al metodo specifico della policy.
     * Gli admin possono fare tutto.
     */
    public function before(User $user, string $ability): bool|null
    {
        // dd('Policy Before - User:', $user->name, 'is_admin:', $user->is_admin, 'Ability:', $ability); // Commenta o rimuovi
        if ($user->is_admin) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */

    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can create models.
     */

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */

    public function update(User $user, Booking $booking): bool
    {
        $isPastBooking = Carbon::parse($booking->start_time)->isPast();

        return $user->id === $booking->user_id && !$isPastBooking;
    }

    /**
     * Determine whether the user can delete the model.
     */

    public function delete(User $user, Booking $booking): bool
    {
        $isPastBooking = Carbon::parse($booking->start_time)->isPast();
        
        return $user->id === $booking->user_id && !$isPastBooking;
    }

}
