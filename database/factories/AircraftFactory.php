<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aircraft>
 */
class AircraftFactory extends Factory
{
    /**
     * Define the model's default state.        
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => $this->faker->company,
            'models' => $this->faker->word,
            'manufacturing_year' => $this->faker->year,
            'acquisition_date' => $this->faker->date(),
            'working_width' => $this->faker->numberBetween(5, 20),
        ];
    }
}
