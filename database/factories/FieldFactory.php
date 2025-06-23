<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Usiamo esattamente i valori definiti nella migrazione del database
        $fieldTypes = ['tennis', 'padel', 'football', 'basket'];
        $type = $this->faker->randomElement($fieldTypes);

        return [
            // Creiamo un nome più realistico, es. "Campo Padel 3"
            'name' => ucfirst($type) . ' ' . $this->faker->unique()->numberBetween(1, 10),
            
            // Aggiungiamo il tipo di campo
            'type' => $type,

            'description' => $this->faker->sentence(),

            // Prezzo: numero intero tra 10 e 50
            'price_per_hour' => $this->faker->numberBetween(10, 50),
            
            // Disponibilità: un valore casuale true o false
            'is_available' => $this->faker->boolean(80),
        ];
    }
}
