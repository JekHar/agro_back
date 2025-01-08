<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parentCategories = [
            'Technology' => 'All about technology and gadgets',
            'Health' => 'Health and wellness topics',
            'Education' => 'Educational resources and news',
            'Business' => 'Business and finance insights',
            'Travel' => 'Travel guides and tips',
        ];

        foreach ($parentCategories as $name => $description) {
            $parentCategory = Category::create([
                'name' => $name,
                'description' => $description,
            ]);

            foreach (range(1, 3) as $index) {
                Category::create([
                    'name' => "{$name} Subcategory {$index}",
                    'description' => "Subcategory {$index} of {$name}",
                    'category_id' => $parentCategory->id,
                ]);
            }
        }
    }
}
