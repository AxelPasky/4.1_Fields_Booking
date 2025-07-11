<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Requests\StoreFieldRequest;
use App\Services\FieldService;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Field::class);
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
    public function store(StoreFieldRequest $request, FieldService $fieldService)
    {
        $validatedData = $request->validated();
        $validatedData['is_available'] = $request->has('is_available');

        // Passiamo anche il file immagine, se presente
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image');
        }

        $fieldService->createField($validatedData);

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
    public function update(StoreFieldRequest $request, Field $field, FieldService $fieldService)
    {
        $validatedData = $request->validated();
        $validatedData['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image');
        }

        $fieldService->updateField($field, $validatedData);

        return redirect()->route('fields.index')->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field, FieldService $fieldService)
    {
        $this->authorize('delete', $field);

        $fieldService->deleteField($field);

        return redirect()->route('fields.index')
                         ->with('success', 'Field and all its associated bookings have been deleted successfully.');
    }
}
