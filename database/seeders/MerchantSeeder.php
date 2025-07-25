<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::factory()->count(10)->create(
            [
                'merchant_type' => 'tenant',
            ]
        );

        $clientMerchantIds = Merchant::where('merchant_type', 'tenant')->pluck('id');
        foreach ($clientMerchantIds as $clientMerchantId) {
            Merchant::factory()->create(
                [
                    'merchant_type' => 'client',
                    'merchant_id' => $clientMerchantId,
                ]
            );
        }
    }
}
