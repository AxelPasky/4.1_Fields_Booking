<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::all(); // Recupera tutti i record dalla tabella 'fields'
        // In futuro, potresti voler aggiungere paginazione qui, es: Field::paginate(10);

        return view('fields.index', ['fields' => $fields]);
        // Passa la variabile $fields alla vista 'fields.index'
        // La vista si aspetterà una variabile chiamata 'fields'
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user() || !auth()->user()->is_admin){
            abort(403,'Azione non autorizzata');
        }

        return view('fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Controllo per l'amministratore
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Azione non autorizzata.');
        }

        // Validazione dei dati
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:tennis,padel,calcio,basket', // Assicurati che i valori corrispondano all'ENUM
            'location' => 'nullable|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255', // Per ora, validiamo solo come stringa
        ]);

        // Creazione del nuovo campo
        Field::create($validatedData);

        // Reindirizzamento alla pagina di elenco con un messaggio di successo
        return redirect()->route('fields.index')
                         ->with('success', 'Campo sportivo creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
    }
}
