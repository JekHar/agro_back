<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;
use App\Models\Lot;
use App\Models\Coordinate;

class LotSeeder extends Seeder
{
    public function run()
    {
        $clientMerchantIds = Merchant::where('merchant_type', 'tenant')->pluck('id');

        foreach ($clientMerchantIds as $clientMerchantId) {
            Lot::factory()->count(10)->create([
                'merchant_id' => $clientMerchantId,
            ]);
        }
    }
}
