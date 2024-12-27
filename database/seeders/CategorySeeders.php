<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Category::factory(5)->create()->each(function ($category) {            
            Category::factory(3)->withParent($category)->create();
        });
    }
}
