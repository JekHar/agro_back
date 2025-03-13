<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Merchant;
use App\Models\Service;
use App\Models\Aircraft;
use App\Models\User;
use App\Models\OrderLot;
use App\Models\Lot;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Flight;

use Illuminate\Database\Eloquent\Factories\Factory;

class SingleOrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'order_number' => 'ORDasdasdasd-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_id' => Merchant::factory(),
            'tenant_id' => Merchant::factory(),
            'service_id' => Service::factory(),
            'aircraft_id' => Aircraft::factory(),
            'pilot_id' => User::factory(),
            'ground_support_id' => User::factory(),
            'total_hectares' => $this->faker->randomFloat(2, 10, 500),
            'total_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'status' => $this->faker->randomElement(['draft', 'pending', 'in_progress', 'completed', 'cancelled']),
            'scheduled_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'observations' => $this->faker->optional()->sentence
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Crear exactamente 2 lotes asociados a la orden
            OrderLot::factory()->count(2)->create([
                'order_id' => $order->id
            ]);

            // Crear exactamente 2 productos asociados a la orden
            OrderProduct::factory()->count(2)->create([
                'order_id' => $order->id
            ]);

            // Crear exactamente 1 vuelo asociado a la orden
            Flight::factory()->create([
                'order_id' => $order->id
            ]);
        });
    }
}
