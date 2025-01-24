<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lot;
use App\Models\Coordinate;

class LotSeeder extends Seeder
{
    public function run()
    {
        // Create 100 lots with their coordinates
        Lot::factory()->count(50)->create();
    }
}