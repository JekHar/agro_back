<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Adherente' => 'Productos que mejoran la adhesión y efectividad de agroquímicos',
            'Fungicida' => 'Productos para el control y prevención de enfermedades fúngicas',
            'Insecticida' => 'Productos para el control de plagas e insectos',
            'Aceite' => 'Aceites agrícolas para diversos usos',
            'Herbicida' => 'Productos para el control de malezas y hierbas no deseadas',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}