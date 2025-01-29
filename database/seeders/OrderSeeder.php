<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Flight;
use App\Models\OrderLot;
use App\Models\OrderProduct;
use App\Models\FlightLot;
use App\Models\FlightProduct;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::factory()->count(50)->create();
    }
}

class FlightSeeder extends Seeder
{
    public function run()
    {
        // Crear 100 vuelos con sus relaciones asociadas
        Flight::factory()->count(100)->create();
    }
}