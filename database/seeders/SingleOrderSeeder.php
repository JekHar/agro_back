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
        // 1. Crear un merchant cliente en Córdoba Capital
        $merchant = Merchant::create([
            'business_name' => 'Cliente Córdoba SRL',
            'trade_name' => 'Cliente Córdoba',
            'fiscal_number' => rand(10000000, 99999999),
            'main_activity' => 'Agricultura',
            'email' => 'cliente.cordoba@example.com',
            'phone' => '+54 351 ' . rand(1000000, 9999999),
            'merchant_type' => 'client',
            'locality' => 'Córdoba Capital',
            'address' => 'Av. Colón 1234, Córdoba Capital',
        ]);
        
        // 2. Crear un lote en Córdoba Capital
        $lot = Lot::create([
            'merchant_id' => $merchant->id,
            'number' => rand(1000, 9999),
            'hectares' => rand(20, 50)
        ]);
        
        // Coordenadas de Córdoba Capital (centro aproximado)
        // Creamos un polígono pequeño alrededor del centro de Córdoba
        $baseLatitude = -31.4201; // Latitud aproximada del centro de Córdoba
        $baseLongitude = -64.1888; // Longitud aproximada del centro de Córdoba
        
        // Calcular el tamaño del lote en grados
        $hectares = $lot->hectares;
        $areaKm2 = $hectares / 100; // Convertir hectáreas a kilómetros cuadrados
        
        // Definir ancho y alto en kilómetros
        $widthKm = sqrt($areaKm2 * 1.5); // Hacer ancho ligeramente mayor
        $heightKm = $areaKm2 / $widthKm; // Altura calculada para mantener el área
        
        // Convertir a grados (aproximadamente)
        $latCorrectionFactor = 111; // km por grado de latitud
        $lonCorrectionFactor = 111 * cos(deg2rad($baseLatitude)); // km por grado de longitud
        
        $heightDegrees = $heightKm / $latCorrectionFactor;
        $widthDegrees = $widthKm / $lonCorrectionFactor;
        
        // Crear las coordenadas en sentido horario
        $coordinates = [
            // Esquina inferior izquierda
            ['latitude' => $baseLatitude, 'longitude' => $baseLongitude],
            // Esquina inferior derecha
            ['latitude' => $baseLatitude, 'longitude' => $baseLongitude + $widthDegrees],
            // Esquina superior derecha
            ['latitude' => $baseLatitude + $heightDegrees, 'longitude' => $baseLongitude + $widthDegrees],
            // Esquina superior izquierda
            ['latitude' => $baseLatitude + $heightDegrees, 'longitude' => $baseLongitude]
        ];
        
        // Guardar las coordenadas en la base de datos
        foreach ($coordinates as $coord) {
            Coordinate::create([
                'lot_id' => $lot->id,
                'latitude' => $coord['latitude'],
                'longitude' => $coord['longitude']
            ]);
        }
        
        // 3. Crear un tenant (proveedor de servicio)
        $tenant = Merchant::where('merchant_type', 'tenant')->first();
        $user2 = User::where('email', 'tenant@example.com')->first();
        
        if ($user2 && $tenant) {
            $user2->merchant_id = $tenant->id;
            $user2->save();
        }
        
        // 4. Crear un servicio
        $service = Service::factory()->create([
            'merchant_id' => $tenant->id
        ]);
        
        // 5. Crear una aeronave
        $aircraft = Aircraft::factory()->create([
            'merchant_id' => $tenant->id
        ]);
        
        // 6. Crear usuario piloto
        $pilot = User::factory()->create([
            'email' => 'piloto.cordoba@example.com'
        ]);
        $pilot->assignRole('Pilot');
        
        // 7. Crear usuario soporte terrestre
        $groundSupport = User::factory()->create([
            'email' => 'soporte.cordoba@example.com'
        ]);
        $groundSupport->assignRole('Ground Support');
        
        // 8. Crear la orden
        $order = Order::create([
            'order_number' => 'ORD-' . rand(1000, 9999),
            'client_id' => $merchant->id,
            'tenant_id' => $user2->merchant_id,
            'service_id' => $service->id,
            'aircraft_id' => $aircraft->id,
            'pilot_id' => $pilot->id,
            'ground_support_id' => $groundSupport->id,
            'total_hectares' => $lot->hectares,
            'total_amount' => $lot->hectares * rand(100, 200), // Precio por hectárea entre 100 y 200
            'status' => 'pending',
            'scheduled_date' => now()->addDays(rand(3, 10)),
            'observations' => 'Orden de prueba para Córdoba Capital'
        ]);
        
        // 9. Asociar el lote a la orden
        OrderLot::create([
            'order_id' => $order->id,
            'lot_id' => $lot->id,
            'hectares' => $lot->hectares
        ]);
        
        // 10. Agregar productos a la orden
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
        
        // 11. Crear un vuelo asociado a la orden
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
        
        // 12. Asociar el lote al vuelo
        FlightLot::create([
            'flight_id' => $flight->id,
            'lot_id' => $lot->id,
            'lot_total_hectares' => $lot->hectares,
            'hectares_to_apply' => $lot->hectares
        ]);
        
        // 13. Asociar los productos al vuelo
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