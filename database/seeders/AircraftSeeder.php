<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use App\Models\Merchant;
use Illuminate\Database\Seeder;

class AircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientMerchantIds = Merchant::where('merchant_type', 'tenant')->pluck('id');

        foreach ($clientMerchantIds as $clientMerchantId) {
            Aircraft::factory()->count(10)->create([
                'merchant_id' => $clientMerchantId,
            ]);
        }
;
    }
}
