<?php

namespace Database\Factories;

use App\Types\MerchantType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchant>
 */
class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => $this->faker->company,
            'trade_name' => $this->faker->companySuffix,
            'fiscal_number' => $this->faker->numerify('########'),
            'main_activity' => $this->faker->words(3, true),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'merchant_type' => $this->faker->randomElement([MerchantType::CLIENT, MerchantType::TENANT]),
            'disabled_at' => null,
            'merchant_id' => null, 
            'locality' => $this->faker->city,
            'address' => $this->faker->address,
        ];
    }
}
