<?php

namespace App\Services;

use App\Models\Field;
use App\Notifications\FieldDeletedNotification;
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

   
    public function updateField(Field $field, array $data): Field
    {
        if (isset($data['image'])) {
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            /** 
             * @var UploadedFile $imageFile 
             * */

            $imageFile = $data['image'];
            $data['image'] = $imageFile->store('fields', 'public');
        }

        $field->update($data);
        return $field;
    }

    
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