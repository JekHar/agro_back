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
            'merchant_id' => Merchant::where('merchant_type', 'tenant')->inRandomOrder()->first()->id,
            'number' => $this->faker->unique()->numberBetween(10000, 99999),
            'hectares' => $this->faker->randomFloat(2, 5, 100)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Lot $lot) {
            // Generate a base point in Argentina's agricultural region
            $baseLat = $this->faker->randomFloat(6, -38, -30);
            $baseLon = $this->faker->randomFloat(6, -65, -58);

            // Calculate dimensions in kilometers first
            $hectares = $lot->hectares;
            $areaKm2 = $hectares / 100; // Convert hectares to square kilometers

            // Calculate width and height in kilometers
            // Make lots typically wider than tall (agricultural plots are often this way)
            $widthKm = sqrt($areaKm2 * 1.5); // Make width slightly larger
            $heightKm = $areaKm2 / $widthKm; // Height calculated to maintain area

            // Convert to degrees (approximately)
            // At these latitudes, 1 degree latitude ≈ 111 km
            // 1 degree longitude ≈ 111 * cos(latitude) km
            $latCorrectionFactor = 111; // km per degree of latitude
            $lonCorrectionFactor = 111 * cos(deg2rad($baseLat)); // km per degree of longitude

            $heightDegrees = $heightKm / $latCorrectionFactor;
            $widthDegrees = $widthKm / $lonCorrectionFactor;

            // Add some randomness to make it less perfectly rectangular (0.9-1.1 variation)
            $widthVariation = $this->faker->randomFloat(2, 0.9, 1.1);
            $heightVariation = $this->faker->randomFloat(2, 0.9, 1.1);

            $widthDegrees *= $widthVariation;
            $heightDegrees *= $heightVariation;

            // Create coordinates in clockwise order
            $coordinates = [
                // Bottom-left
                ['latitude' => $baseLat, 'longitude' => $baseLon],
                // Bottom-right
                ['latitude' => $baseLat, 'longitude' => $baseLon + $widthDegrees],
                // Top-right
                ['latitude' => $baseLat + $heightDegrees, 'longitude' => $baseLon + $widthDegrees],
                // Top-left
                ['latitude' => $baseLat + $heightDegrees, 'longitude' => $baseLon],
            ];

            // Add slight irregularities to make it look more natural
            foreach ($coordinates as $coord) {
                Coordinate::create([
                    'lot_id' => $lot->id,
                    'latitude' => $coord['latitude'] + $this->faker->randomFloat(6, -0.0001, 0.0001),
                    'longitude' => $coord['longitude'] + $this->faker->randomFloat(6, -0.0001, 0.0001)
                ]);
            }
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
            'latitude' => $this->faker->randomFloat(6, -38, -30),
            'longitude' => $this->faker->randomFloat(6, -65, -58)
        ];
    }
}
