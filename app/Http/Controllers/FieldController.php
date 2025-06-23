<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FieldDeletedNotification;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('fields.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Field::class);
        return view('fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Field::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:fields',
            'price_per_hour' => 'required|numeric|min:0',
            'is_available' => 'sometimes|boolean', // Aggiungiamo la validazione
        ]);

        // Creiamo il campo usando i dati validati
        Field::create([
            'name' => $validatedData['name'],
            'price_per_hour' => $validatedData['price_per_hour'],
            // Se il checkbox è spuntato, il request avrà 'is_available'. Altrimenti no.
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('fields.index')->with('success', 'Field created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        $this->authorize('view', $field);
        return view('fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        $this->authorize('update', $field);
        return view('fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        $this->authorize('update', $field);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Validazione immagine
            'is_available' => 'sometimes|boolean',
        ]);

        $validatedData['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Salva il file in storage/app/public/fields e ottieni il percorso
            $path = $request->file('image')->store('fields', 'public');
            $validatedData['image'] = $path;
        }

        $field->update($validatedData);

        return redirect()->route('fields.index')
                         ->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        $this->authorize('delete', $field);

        // Trova tutti gli utenti unici che hanno prenotato questo campo
        $usersToNotify = $field->bookings()->with('user')->get()->pluck('user')->unique();

        // Invia la notifica a tutti gli utenti interessati
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new FieldDeletedNotification($field));
        }

        // Cancella tutte le prenotazioni associate
        $field->bookings()->delete();

        // Cancella l'immagine associata, se esiste
        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        // Cancella il campo
        $field->delete();

        return redirect()->route('fields.index')
                         ->with('success', 'Field and all its associated bookings have been deleted successfully.');
    }
}
