<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $field = Field::inRandomOrder()->first() ?? Field::factory()->create();

         
        $bookingDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        
        
        $startHour = $this->faker->numberBetween(8, 20);
        $startTime = Carbon::instance($bookingDate)->setTime($startHour, 0, 0);

       
        $durationInHours = $this->faker->randomElement([1, 2]);
        $endTime = $startTime->copy()->addHours($durationInHours);

        
        $totalPrice = $field->price_per_hour * $durationInHours;

        return [
            'user_id' => $user->id,
            'field_id' => $field->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
