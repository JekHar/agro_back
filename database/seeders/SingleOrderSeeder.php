<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Merchant;
use App\Models\Service;
use App\Models\Aircraft;
use App\Models\User;
use App\Models\Lot;
use App\Models\Coordinate;
use App\Models\OrderLot;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Flight;
use App\Models\FlightLot;
use App\Models\FlightProduct;

class SingleOrderSeeder extends Seeder
{
    public function run()
    {
        // Use existing client merchant instead of creating a new one
        $merchant = Merchant::where('merchant_type', 'client')->inRandomOrder()->first();

        if (!$merchant) {
            $this->command->warn('No client merchants found. Make sure MerchantSeeder has been run.');
            return;
        }

        $lot = Lot::create([
            'merchant_id' => $merchant->id,
            'number' => rand(1000, 9999),
            'hectares' => rand(20, 50)
        ]);

        $baseLatitude = -31.4201;
        $baseLongitude = -64.1888;

        $hectares = $lot->hectares;
        $areaKm2 = $hectares / 100;

        $widthKm = sqrt($areaKm2 * 1.5);
        $heightKm = $areaKm2 / $widthKm;

        $latCorrectionFactor = 111;
        $lonCorrectionFactor = 111 * cos(deg2rad($baseLatitude));

        $heightDegrees = $heightKm / $latCorrectionFactor;
        $widthDegrees = $widthKm / $lonCorrectionFactor;

        $coordinates = [
            ['latitude' => $baseLatitude, 'longitude' => $baseLongitude],
            ['latitude' => $baseLatitude, 'longitude' => $baseLongitude + $widthDegrees],
            ['latitude' => $baseLatitude + $heightDegrees, 'longitude' => $baseLongitude + $widthDegrees],
            ['latitude' => $baseLatitude + $heightDegrees, 'longitude' => $baseLongitude]
        ];

        foreach ($coordinates as $coord) {
            Coordinate::create([
                'lot_id' => $lot->id,
                'latitude' => $coord['latitude'],
                'longitude' => $coord['longitude']
            ]);
        }

        $tenant = Merchant::where('merchant_type', 'tenant')->first();
        if (!$tenant) {
            $this->command->warn('No tenant merchants found. Make sure MerchantSeeder has been run.');
            return;
        }

        $user2 = User::where('email', 'tenant@example.com')->first();

        if ($user2 && $tenant) {
            $user2->merchant_id = $tenant->id;
            $user2->save();
        }

        $service = Service::where('merchant_id', $tenant->id)->first() ?:
            Service::factory()->create([
                'merchant_id' => $tenant->id
            ]);

        $aircraft = Aircraft::where('merchant_id', $tenant->id)->first() ?:
            Aircraft::factory()->create([
                'merchant_id' => $tenant->id
            ]);

        $pilot = User::factory()->create([
            'email' => 'piloto.cordoba@example.com'
        ]);
        $pilot->assignRole('Pilot');

        $groundSupport = User::factory()->create([
            'email' => 'soporte.cordoba@example.com'
        ]);
        $groundSupport->assignRole('Ground Support');

        $order = Order::create([
            'order_number' => 'ORD-' . rand(1000, 9999),
            'client_id' => $merchant->id,
            'tenant_id' => $user2->merchant_id,
            'service_id' => $service->id,
            'aircraft_id' => $aircraft->id,
            'pilot_id' => $pilot->id,
            'ground_support_id' => $groundSupport->id,
            'total_hectares' => $lot->hectares,
            'total_amount' => $lot->hectares * rand(100, 200),
            'status' => 'pending',
            'scheduled_date' => now()->addDays(rand(3, 10)),
            'observations' => 'Orden de prueba para Córdoba Capital'
        ]);

        OrderLot::create([
            'order_id' => $order->id,
            'lot_id' => $lot->id,
            'hectares' => $lot->hectares
        ]);

        $products = Product::inRandomOrder()->take(2)->get();
        if ($products->isEmpty()) {
            $products = Product::factory()->count(2)->create();
        }

        foreach ($products as $product) {
            $dosage = rand(1, 5);
            $clientQuantity = $lot->hectares * $dosage;

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'client_provided_quantity' => $clientQuantity,
                'manual_total_quantity' => $clientQuantity,
                'manual_dosage_per_hectare' => $dosage,
                'total_quantity_to_use' => $clientQuantity,
                'calculated_dosage' => $dosage,
                'product_difference' => 0,
                'difference_observation' => null
            ]);
        }

        $flight = Flight::create([
            'order_id' => $order->id,
            'flight_number' =>  rand(1000, 9999),
            'total_hectares' => $lot->hectares,
            'status' => 'pending',
            'started_at' => null,
            'completed_at' => null,
            'observations' => 'Vuelo programado para Córdoba Capital',
            'weather_conditions' => [
                'temperature' => rand(15, 28),
                'wind_speed' => rand(2, 15),
                'humidity' => rand(40, 80),
                'precipitation' => 0
            ]
        ]);

        FlightLot::create([
            'flight_id' => $flight->id,
            'lot_id' => $lot->id,
            'lot_total_hectares' => $lot->hectares,
            'hectares_to_apply' => $lot->hectares
        ]);

        foreach ($products as $product) {
            $orderProduct = OrderProduct::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            FlightProduct::create([
                'flight_id' => $flight->id,
                'product_id' => $product->id,
                'quantity' => $orderProduct ? $orderProduct->total_quantity_to_use : ($lot->hectares * rand(1, 5))
            ]);
        }

        $this->command->info('Se ha creado una orden en Córdoba Capital con éxito.');
    }
}
