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
       
        $fieldTypes = ['tennis', 'padel', 'football', 'basket'];
        $type = $this->faker->randomElement($fieldTypes);

        return [
           
            'name' => ucfirst($type) . ' ' . $this->faker->unique()->numberBetween(1, 10),
            'type' => $type,
            'description' => $this->faker->sentence(),
            'price_per_hour' => $this->faker->numberBetween(10, 50),
            'is_available' => $this->faker->boolean(80),
        ];
    }
}
