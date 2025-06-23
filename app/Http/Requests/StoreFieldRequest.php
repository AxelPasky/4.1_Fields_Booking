<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Se stiamo creando un campo (metodo POST), controlliamo il permesso 'create'.
        // Se stiamo aggiornando (metodo PUT/PATCH), controlliamo il permesso 'update' sul campo specifico.
        if ($this->isMethod('post')) {
            return $this->user()->can('create', \App\Models\Field::class);
        }

        // $this->field è il model che Laravel passa automaticamente dalla rotta (es. /fields/{field})
        return $this->user()->can('update', $this->field);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Qui definiamo le nostre regole, una volta sola.
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Ignora l'ID del campo corrente durante l'aggiornamento per evitare errori di "unique".
                // Se stiamo creando, $this->field non esiste e viene ignorato.
                Rule::unique('fields')->ignore($this->field),
            ],
            'type' => ['required', Rule::in(['tennis', 'padel', 'football', 'basket'])],
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_available' => 'sometimes|boolean',
        ];
    }
}
