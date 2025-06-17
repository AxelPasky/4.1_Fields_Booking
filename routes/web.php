<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route per i Fields (Campi Sportivi)
Route::resource('fields', FieldController::class)
    ->middleware(['auth']); // Proteggiamo tutte le route dei campi con autenticazione per ora
                           // Più avanti, affineremo l'autorizzazione per gli admin

// Route per le Bookings (Prenotazioni)
Route::resource('bookings', BookingController::class)
    ->middleware(['auth']); // Proteggiamo tutte le route delle prenotazioni con autenticazione

require __DIR__.'/auth.php';
