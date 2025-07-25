<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientMerchantIds = Merchant::where('merchant_type', 'tenant')->pluck('id');
        if ($clientMerchantIds->isEmpty()) {
            $this->command->warn('No merchants of type "tenant" found. Please seed Merchants first.');
            return;
        }
        foreach ($clientMerchantIds as $clientMerchantId) {

            Service::factory()->count(10)->create([
                'merchant_id' => $clientMerchantId,
            ]);
        }
    }
}
