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
use App\Models\FlightLot;
use App\Models\FlightProduct;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_id' => Merchant::factory(),
            'tenant_id' => Merchant::factory(),
            'service_id' => Service::factory(),
            'aircraft_id' => Aircraft::factory(),
            'pilot_id' => User::factory(),
            'ground_support_id' => User::factory(),
            'total_hectares' => $this->faker->randomFloat(2, 10, 500),
            'total_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'status' => $this->faker->randomElement(['draft','pending', 'in_progress', 'completed', 'cancelled']),
            'scheduled_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'observations' => $this->faker->optional()->sentence
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Crear OrderLots
            OrderLot::factory()->count(rand(1, 3))->create([
                'order_id' => $order->id
            ]);

            // Crear OrderProducts
            OrderProduct::factory()->count(rand(1, 3))->create([
                'order_id' => $order->id
            ]);

            // Crear Flights
            Flight::factory()->count(rand(1, 2))->create([
                'order_id' => $order->id
            ]);
        });
    }
}

class OrderLotFactory extends Factory
{
    protected $model = OrderLot::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'lot_id' => Lot::factory(),
            'hectares' => $this->faker->randomFloat(2, 5, 100)
        ];
    }
}

class OrderProductFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition()
    {
        $clientProvidedQuantity = $this->faker->randomFloat(2, 10, 500);
        $calculatedDosage = $this->faker->randomFloat(2, 0.5, 5);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'client_provided_quantity' => $clientProvidedQuantity,
            'manual_total_quantity' => $this->faker->randomFloat(2, $clientProvidedQuantity, $clientProvidedQuantity * 1.2),
            'manual_dosage_per_hectare' => $calculatedDosage,
            'total_quantity_to_use' => $this->faker->randomFloat(2, 10, 500),
            'calculated_dosage' => $calculatedDosage,
            'product_difference' => $this->faker->randomFloat(2, -10, 10),
            'difference_observation' => $this->faker->optional()->sentence
        ];
    }
}

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'flight_number' => $this->faker->unique()->numerify('#####'),
            'total_hectares' => $this->faker->randomFloat(2, 10, 300),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'started_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'observations' => $this->faker->optional()->sentence,
            'weather_conditions' => [
                'temperature' => $this->faker->randomFloat(1, 10, 35),
                'wind_speed' => $this->faker->randomFloat(1, 0, 20),
                'humidity' => $this->faker->randomFloat(1, 30, 90),
                'precipitation' => $this->faker->randomElement([0, 0, 0, $this->faker->randomFloat(1, 0, 10)])
            ]
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Flight $flight) {
            // Crear FlightLots
            FlightLot::factory()->count(rand(1, 3))->create([
                'flight_id' => $flight->id
            ]);

            // Crear FlightProducts
            FlightProduct::factory()->count(rand(1, 3))->create([
                'flight_id' => $flight->id
            ]);
        });
    }
}

class FlightLotFactory extends Factory
{
    protected $model = FlightLot::class;

    public function definition()
    {
        return [
            'flight_id' => Flight::factory(),
            'lot_id' => Lot::factory(),
            'lot_total_hectares' => $this->faker->randomFloat(2, 10, 200),
            'hectares_to_apply' => $this->faker->randomFloat(2, 5, 100)
        ];
    }
}

class FlightProductFactory extends Factory
{
    protected $model = FlightProduct::class;

    public function definition()
    {
        return [
            'flight_id' => Flight::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->randomFloat(2, 10, 500)
        ];
    }
}