<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Booking;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            return $this->user()->can('create', Booking::class);
        }

        return $this->user()->can('update', $this->booking);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $thirtyMinuteRule = function ($attribute, $value, $fail) {
            if (Carbon::parse($value)->minute % 30 !== 0) {
                $fail('The ' . str_replace('_', ' ', $attribute) . ' must be in 30-minute increments (e.g., 09:00, 09:30).');
            }
        };

        $rules = [
            'field_id' => ['required'],
            'booking_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i', $thirtyMinuteRule],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time', $thirtyMinuteRule],
        ];

        // Aggiunge regole specifiche per la creazione
        if ($this->isMethod('post')) {
            $rules['field_id'][] = Rule::exists('fields', 'id')->where('is_available', true);
            $rules['booking_date'][] = 'after_or_equal:today';
        } else {
            $rules['field_id'][] = 'exists:fields,id';
        }

        // Aggiunge la validazione per le sovrapposizioni direttamente qui
        $rules['start_time'][] = function ($attribute, $value, $fail) {
            $startDateTime = Carbon::parse($this->input('booking_date') . ' ' . $this->input('start_time'));
            $endDateTime = Carbon::parse($this->input('booking_date') . ' ' . $this->input('end_time'));

            $query = Booking::where('field_id', $this->input('field_id'))
                ->where('start_time', '<', $endDateTime)
                ->where('end_time', '>', $startDateTime);

            // Se stiamo aggiornando, escludiamo la prenotazione corrente dal controllo
            if ($this->booking) {
                $query->where('id', '!=', $this->booking->id);
            }

            if ($query->exists()) {
                $fail('The selected time slot is already booked or overlaps with an existing booking.');
            }
        };

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'field_id.exists' => 'The selected field is not available or does not exist.',
            'booking_date.after_or_equal' => 'The booking date cannot be in the past.',
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }
}
