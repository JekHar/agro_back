<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'sku' => strtoupper($this->faker->unique()->lexify('SKU-????')),
            'category_id' => Category::inRandomOrder()->first()->id,
            'merchant_id' => Merchant::where('merchant_type', 'client')->inRandomOrder()->first()->id,
            'concentration' => $this->faker->numberBetween(1, 100),
            'dosage_per_hectare' => $this->faker->randomFloat(2, 5, 20),
            'application_volume_per_hectare' => $this->faker->randomFloat(2, 100, 200),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}
