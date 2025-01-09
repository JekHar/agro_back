<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test Tenant User',
            'email' => 'tenant@example.com',
            'password' => bcrypt('password'),
        ]);

        $user3 = User::factory()->create([
            'name' => 'Test Pilot User',
            'email' => 'pilot@example.com',
            'password' => bcrypt('password'),
        ]);

        $user4 = User::factory()->create([
            'name' => 'Test Ground Support User',
            'email' => 'ground@example.com',
            'password' => bcrypt('password'),
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

        ]);
        $user1->assignRole('Admin');
        $user2->assignRole('Tenant');
        $user3->assignRole('Pilot');
        $user4->assignRole('Ground Support');
    }
}
