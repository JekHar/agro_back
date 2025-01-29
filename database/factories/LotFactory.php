<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\Merchant;
use App\Models\Coordinate;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    protected $model = Lot::class;

    public function definition()
    {
        return [
            'merchant_id' => Merchant::factory(),
            'number' => $this->faker->unique()->numerify('#####'),
            'hectares' => $this->faker->randomFloat(2, 10, 500)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Lot $lot) {
            // Create 4-8 coordinates for each lot
            Coordinate::factory()->count($this->faker->numberBetween(4, 8))->create([
                'lot_id' => $lot->id
            ]);
        });
    }
}

class CoordinateFactory extends Factory
{
    protected $model = Coordinate::class;

    public function definition()
    {
        return [
            'lot_id' => Lot::factory(),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude
        ];
    }
}