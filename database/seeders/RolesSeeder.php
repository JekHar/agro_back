<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Tenant'],
            ['id' => 3, 'name' => 'Pilot'],
            ['id' => 4, 'name' => 'Ground Support'],
        ];

        foreach ($roles as $role) {
            Role::create([
                'id' => $role['id'], 
                'name' => $role['name'],
            ]);
        }
    }
}