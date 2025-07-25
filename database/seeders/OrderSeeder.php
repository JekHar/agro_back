<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Flight;
use App\Models\OrderLot;
use App\Models\OrderProduct;
use App\Models\FlightLot;
use App\Models\FlightProduct;
use App\Models\Merchant;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Check if merchants exist
        $tenantCount = Merchant::where('merchant_type', 'tenant')->count();
        $clientCount = Merchant::where('merchant_type', 'client')->count();

        if ($tenantCount == 0 || $clientCount == 0) {
            $this->command->warn('No merchants found. Make sure MerchantSeeder has been run.');
            return;
        }

        Order::factory()->count(50)->create();
    }
}

class FlightSeeder extends Seeder
{
    public function run()
    {
        // Use existing orders instead of creating new ones with new merchants
        $existingOrders = Order::all();

        if ($existingOrders->isEmpty()) {
            $this->command->warn('No orders found to create flights for. Run OrderSeeder first.');
            return;
        }

        foreach ($existingOrders as $order) {
            Flight::factory()->create([
                'order_id' => $order->id
            ]);
        }
    }
}
