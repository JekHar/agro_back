<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('production')) {
            $this->call([
                RolesSeeder::class,
                PermissionsSeeder::class,
                CategorySeeders::class,
                RoleHasPermissionsSeeder::class,
            ]);

            // Crear usuario admin en producción
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com', 
                'password' => Hash::make('password'),
            ]);

            $admin->assignRole('Admin');

        } else {

            $user1 = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]);

            $user2 = User::factory()->create([
                'name' => 'Test Tenant User',
                'email' => 'tenant@example.com',
                'password' => Hash::make('password'),
            ]);

            $user3 = User::factory()->create([
                'name' => 'Test Pilot User',
                'email' => 'pilot@example.com',
                'password' => Hash::make('password'),
            ]);

            $user4 = User::factory()->create([
                'name' => 'Test Ground Support User',
                'email' => 'ground@example.com',
                'password' => Hash::make('password'),
            ]);

           
            $this->call([
                RolesSeeder::class,
                PermissionsSeeder::class,
                RoleHasPermissionsSeeder::class,
                MerchantSeeder::class,
                ServiceSeeder::class,
                AircraftSeeder::class,
                UserSeeder::class,
                CategorySeeders::class,
                ProductSeeders::class,
                LotSeeder::class,
                OrderSeeder::class,
                //SingleOrderSeeder::class,
                FlightSeeder::class
            ]);


            $user1->assignRole('Admin');
            $user2->assignRole('Tenant');
            $user3->assignRole('Pilot');
            $user4->assignRole('Ground Support');
        }
    }
}
