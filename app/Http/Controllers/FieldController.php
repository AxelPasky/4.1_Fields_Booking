<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Field::class);
        $fields = Field::latest()->paginate(10);
        return view('fields.index', compact('fields'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validazione immagine
            'is_available' => 'sometimes|boolean',
        ]);

        // Gestisce correttamente il valore del checkbox 'is_available'
        $validatedData['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Salva il file in storage/app/public/fields e ottieni il percorso
            $path = $request->file('image')->store('fields', 'public');
            $validatedData['image'] = $path;
        }

        Field::create($validatedData);

        return redirect()->route('fields.index')
                         ->with('success', 'Field created successfully.');
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

        // Impedisce la cancellazione se il campo ha prenotazioni associate
        if ($field->bookings()->exists()) {
            return redirect()->route('fields.index')
                             ->with('error', 'Cannot delete this field because it has existing bookings.');
        }

        $field->delete();

        return redirect()->route('fields.index')
                         ->with('success', 'Field deleted successfully.');
    }
}
