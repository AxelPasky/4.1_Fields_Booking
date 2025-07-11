<?php

namespace App\Services;

use App\Models\Field;
use App\Notifications\FieldDeletedNotification;
use App\Notifications\FieldUnavailableForBookingNotification; // <-- Importa la nuova notifica
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class FieldService
{
    public function createField(array $data): Field
    {
        if (isset($data['image'])) {
            /** @var UploadedFile $imageFile */
            $imageFile = $data['image'];
            $data['image'] = $imageFile->store('fields', 'public');
        }

        return Field::create($data);
    }

    /**
     * Update an existing field and handle related logic.
     *
     * @param Field $field
     * @param array $validatedData
     * @return void
     */
    public function updateField(Field $field, array $validatedData): void
    {
        $wasAvailable = $field->is_available;

        if (isset($validatedData['image'])) {
            // Delete old image if it exists
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            // Store new image
            $validatedData['image'] = $validatedData['image']->store('field_images', 'public');
        }

        $field->update($validatedData);

        // Check if the field was made unavailable
        if ($wasAvailable && !$field->is_available) {
            $this->cancelFutureBookingsForField($field);
        }
    }

    /**
     * Cancel all future bookings for a specific field and notify users.
     *
     * @param Field $field
     * @return void
     */
    private function cancelFutureBookingsForField(Field $field): void
    {
        // Find future bookings for this field
        $futureBookings = $field->bookings()->where('start_time', '>', now())->get();

        if ($futureBookings->isEmpty()) {
            return;
        }

        // Get unique users to notify
        $usersToNotify = $futureBookings->pluck('user')->unique()->filter();

        // Notify users
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new FieldUnavailableForBookingNotification($field));
        }

        // Delete the bookings
        $field->bookings()->where('start_time', '>', now())->delete();
    }

    /**
     * Delete a field and its related data.
     *
     * @param Field $field
     * @return void
     */
    public function deleteField(Field $field): void
    {
        $usersToNotify = $field->bookings()->with('user')->get()->pluck('user')->unique();
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new FieldDeletedNotification($field));
        }

        $field->bookings()->delete();

        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();
    }
}