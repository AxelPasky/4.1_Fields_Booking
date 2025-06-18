<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     * Qualsiasi utente autenticato può vedere l'elenco dei campi.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Qualsiasi utente autenticato può vedere i dettagli di un campo.
     */
    public function view(User $user, Field $field): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Solo un admin può creare un campo.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     * Solo un admin può aggiornare un campo.
     */
    public function update(User $user, Field $field): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     * Solo un admin può eliminare un campo.
     */
    public function delete(User $user, Field $field): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Field $field): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Field $field): bool
    {
        return $user->is_admin;
    }
}
