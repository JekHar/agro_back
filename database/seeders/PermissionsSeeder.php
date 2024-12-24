<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // CRUD Merchants
            ['id' => 1, 'name' => 'merchants.create'],
            ['id' => 2, 'name' => 'merchants.edit'],
            ['id' => 3, 'name' => 'merchants.delete'],
            ['id' => 4, 'name' => 'merchants.index'],
            ['id' => 5, 'name' => 'merchants.show'],
            ['id' => 6, 'name' => 'merchants.store'],

            // CRUD Products
            ['id' => 7, 'name' => 'products.create'],
            ['id' => 8, 'name' => 'products.edit'],
            ['id' => 9, 'name' => 'products.delete'],
            ['id' => 10, 'name' => 'products.index'],
            ['id' => 11, 'name' => 'products.show'],
            ['id' => 12, 'name' => 'products.store'],

            // CRUD Aircrafts
            ['id' => 13, 'name' => 'aircraft.create'],
            ['id' => 14, 'name' => 'aircraft.edit'],
            ['id' => 15, 'name' => 'aircraft.delete'],
            ['id' => 16, 'name' => 'aircraft.index'],
            ['id' => 17, 'name' => 'aircraft.show'],
            ['id' => 18, 'name' => 'aircraft.store'],

            // CRUD Orders
            ['id' => 19, 'name' => 'orders.create'],
            ['id' => 20, 'name' => 'orders.edit'],
            ['id' => 21, 'name' => 'orders.delete'],
            ['id' => 22, 'name' => 'orders.index'],
            ['id' => 23, 'name' => 'orders.show'],
            ['id' => 24, 'name' => 'orders.store'],

            // CRUD Categories
            ['id' => 25, 'name' => 'categories.create'],
            ['id' => 26, 'name' => 'categories.edit'],
            ['id' => 27, 'name' => 'categories.delete'],
            ['id' => 28, 'name' => 'categories.index'],
            ['id' => 29, 'name' => 'categories.show'],
            ['id' => 30, 'name' => 'categories.store'],

            // CRUD Users
            ['id' => 31, 'name' => 'users.create'],
            ['id' => 32, 'name' => 'users.edit'],
            ['id' => 33, 'name' => 'users.delete'],
            ['id' => 34, 'name' => 'users.index'],
            ['id' => 35, 'name' => 'users.show'],
            ['id' => 36, 'name' => 'users.store'],

            // CRUD Coordinates
            ['id' => 37, 'name' => 'coordinates.create'],
            ['id' => 38, 'name' => 'coordinates.edit'],
            ['id' => 39, 'name' => 'coordinates.delete'],
            ['id' => 40, 'name' => 'coordinates.index'],
            ['id' => 41, 'name' => 'coordinates.show'],
            ['id' => 42, 'name' => 'coordinates.store'],

            // CRUD Lots
            ['id' => 43, 'name' => 'lots.create'],
            ['id' => 44, 'name' => 'lots.edit'],
            ['id' => 45, 'name' => 'lots.delete'],
            ['id' => 46, 'name' => 'lots.index'],
            ['id' => 47, 'name' => 'lots.show'],
            ['id' => 48, 'name' => 'lots.store'],

            // CRUD Services
            ['id' => 49, 'name' => 'services.create'],
            ['id' => 50, 'name' => 'services.edit'],
            ['id' => 51, 'name' => 'services.delete'],
            ['id' => 52, 'name' => 'services.index'],
            ['id' => 53, 'name' => 'services.show'],
            ['id' => 54, 'name' => 'services.store'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['id' => $permission['id'], 'name' => $permission['name']]);
        }
    }
}