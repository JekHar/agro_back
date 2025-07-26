<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Run CategorySeeder first.');

            return;
        }

        $merchants = Merchant::where('merchant_type', 'tenant')->get();

        $categories->each(function ($category) use ($merchants) {
            $merchants->each(function ($merchant) use ($category) {
                Product::factory(1)->create([
                    'category_id' => $category->id,
                    'merchant_id' => $merchant->id,
                ]);
            });
        });
    }
}
