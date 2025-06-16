<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::all();
        return view('fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        return view('fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string|max:255', // Temporary string
            'price_per_hour' => 'required|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        // If 'is_available' is not present in the request, set it to false (or 0)
        $validatedData['is_available'] = $request->has('is_available');


        Field::create($validatedData);

        return redirect()->route('fields.index')->with('success', 'Field created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        return view('fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        return view('fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string|max:255', // Temporary string
            'price_per_hour' => 'required|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        // If 'is_available' is not present in the request, set it to false (or 0)
        // For checkboxes, if they are not sent in the request, it means they are unchecked (false).
        $validatedData['is_available'] = $request->has('is_available');

        $field->update($validatedData);

        return redirect()->route('fields.show', $field)->with('success', 'Field updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        // Optional: Add logic here to check for related bookings before deleting a field.
        // For example, prevent deletion if there are active or future bookings for this field,
        // or delete/archive related bookings.
        // For now, we'll proceed with a simple deletion.

        $field->delete();

        return redirect()->route('fields.index')->with('success', 'Field deleted successfully!');
    }
}
