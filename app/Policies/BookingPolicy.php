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
     * Tutti gli utenti autenticati possono accedere all'elenco (il controller poi filtrerà).
     */
    public function viewAny(User $user): bool
    {
        // dd('Policy viewAny - User:', $user->name); // Commenta o rimuovi
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * L'admin può (gestito da before). L'utente normale può vedere solo le proprie.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can create models.
     * Tutti gli utenti autenticati possono creare prenotazioni.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * L'admin può (gestito da before). L'utente normale può modificare solo le proprie,
     * e solo se la prenotazione non è nel passato.
     */
    public function update(User $user, Booking $booking): bool
    {
        // La prenotazione non deve essere nel passato per essere modificata
        $isPastBooking = Carbon::parse($booking->start_time)->isPast();

        return $user->id === $booking->user_id && !$isPastBooking;
    }

    /**
     * Determine whether the user can delete the model.
     * L'admin può (gestito da before). L'utente normale può cancellare solo le proprie,
     * e solo se la prenotazione non è nel passato.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // La prenotazione non deve essere nel passato per essere cancellata
        $isPastBooking = Carbon::parse($booking->start_time)->isPast();
        
        return $user->id === $booking->user_id && !$isPastBooking;
    }

    /**
     * Determine whether the user can restore the model.
     * (Non implementato per ora, ma l'admin potrebbe farlo)
     */
    public function restore(User $user, Booking $booking): bool
    {
        // return $user->is_admin; // Esempio
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * (Non implementato per ora, ma l'admin potrebbe farlo)
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        // return $user->is_admin; // Esempio
        return false;
    }
}
