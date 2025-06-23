<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Field;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea l'utente Amministratore
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ricorda questa password!
            'is_admin' => true,
        ]);

        // Crea un utente normale
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // Ricorda questa password!
        ]);

        // Crea 5 campi usando la factory
        Field::factory(5)->create();
    }
}
