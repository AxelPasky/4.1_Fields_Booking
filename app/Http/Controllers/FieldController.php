<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Requests\StoreFieldRequest; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FieldDeletedNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
    public function store(StoreFieldRequest $request) 
    {
       
        $validatedData = $request->validated();

        $validatedData['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('fields', 'public');
            $validatedData['image'] = $path;
        }

        Field::create($validatedData);

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
    public function update(StoreFieldRequest $request, Field $field) 
    {
    
        $validatedData = $request->validated();

        $validatedData['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
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

        $usersToNotify = $field->bookings()->with('user')->get()->pluck('user')->unique();

        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new FieldDeletedNotification($field));
        }

        $field->bookings()->delete();

        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('fields.index')
                         ->with('success', 'Field and all its associated bookings have been deleted successfully.');
    }
}
