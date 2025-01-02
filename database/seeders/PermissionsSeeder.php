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

            // CRUD Clients' Merchants
            ['id' => 7, 'name' => 'clients.merchants.create'],
            ['id' => 8, 'name' => 'clients.merchants.edit'],
            ['id' => 9, 'name' => 'clients.merchants.delete'],
            ['id' => 10, 'name' => 'clients.merchants.index'],
            ['id' => 11, 'name' => 'clients.merchants.show'],
            ['id' => 12, 'name' => 'clients.merchants.store'],

            // CRUD Tenants' Merchants
            ['id' => 13, 'name' => 'tenants.merchants.create'],
            ['id' => 14, 'name' => 'tenants.merchants.edit'],
            ['id' => 15, 'name' => 'tenants.merchants.delete'],
            ['id' => 16, 'name' => 'tenants.merchants.index'],
            ['id' => 17, 'name' => 'tenants.merchants.show'],
            ['id' => 18, 'name' => 'tenants.merchants.store'],

            // CRUD Products
            ['id' => 19, 'name' => 'products.create'],
            ['id' => 20, 'name' => 'products.edit'],
            ['id' => 21, 'name' => 'products.delete'],
            ['id' => 22, 'name' => 'products.index'],
            ['id' => 23, 'name' => 'products.show'],
            ['id' => 24, 'name' => 'products.store'],

            // CRUD Aircrafts
            ['id' => 25, 'name' => 'aircraft.create'],
            ['id' => 26, 'name' => 'aircraft.edit'],
            ['id' => 27, 'name' => 'aircraft.delete'],
            ['id' => 28, 'name' => 'aircraft.index'],
            ['id' => 29, 'name' => 'aircraft.show'],
            ['id' => 30, 'name' => 'aircraft.store'],

            // CRUD Orders
            ['id' => 31, 'name' => 'orders.create'],
            ['id' => 32, 'name' => 'orders.edit'],
            ['id' => 33, 'name' => 'orders.delete'],
            ['id' => 34, 'name' => 'orders.index'],
            ['id' => 35, 'name' => 'orders.show'],
            ['id' => 36, 'name' => 'orders.store'],

            // CRUD Categories
            ['id' => 37, 'name' => 'categories.create'],
            ['id' => 38, 'name' => 'categories.edit'],
            ['id' => 39, 'name' => 'categories.delete'],
            ['id' => 40, 'name' => 'categories.index'],
            ['id' => 41, 'name' => 'categories.show'],
            ['id' => 42, 'name' => 'categories.store'],

            // CRUD Users
            ['id' => 43, 'name' => 'users.create'],
            ['id' => 44, 'name' => 'users.edit'],
            ['id' => 45, 'name' => 'users.delete'],
            ['id' => 46, 'name' => 'users.index'],
            ['id' => 47, 'name' => 'users.show'],
            ['id' => 48, 'name' => 'users.store'],

            // CRUD Coordinates
            ['id' => 49, 'name' => 'coordinates.create'],
            ['id' => 50, 'name' => 'coordinates.edit'],
            ['id' => 51, 'name' => 'coordinates.delete'],
            ['id' => 52, 'name' => 'coordinates.index'],
            ['id' => 53, 'name' => 'coordinates.show'],
            ['id' => 54, 'name' => 'coordinates.store'],

            // CRUD Lots
            ['id' => 55, 'name' => 'lots.create'],
            ['id' => 56, 'name' => 'lots.edit'],
            ['id' => 57, 'name' => 'lots.delete'],
            ['id' => 58, 'name' => 'lots.index'],
            ['id' => 59, 'name' => 'lots.show'],
            ['id' => 60, 'name' => 'lots.store'],

            // CRUD Services
            ['id' => 61, 'name' => 'services.create'],
            ['id' => 62, 'name' => 'services.edit'],
            ['id' => 63, 'name' => 'services.delete'],
            ['id' => 64, 'name' => 'services.index'],
            ['id' => 65, 'name' => 'services.show'],
            ['id' => 66, 'name' => 'services.store'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['id' => $permission['id'], 'name' => $permission['name']]);
        }
    }
}
