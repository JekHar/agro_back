<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get all merchants
        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            // Create tenant user for merchants that are of type 'tenant'
            if ($merchant->merchant_type === 'tenant') {
                $tenant = User::create([
                    'name' => "Tenant {$merchant->business_name}",
                    'email' => "tenant.{$merchant->id}@" . strtolower(str_replace(' ', '', $merchant->business_name)) . ".com",
                    'password' => Hash::make('password'),
                    'merchant_id' => $merchant->id,
                ]);
                $tenant->assignRole('Tenant');
            }

            // Create Pilot and Ground Support for each merchant
            $roles = ['Pilot', 'Ground Support'];
            foreach ($roles as $role) {
                $user = User::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => Hash::make('password'),
                    'merchant_id' => $merchant->id,
                ]);

                $user->assignRole($role);
            }

            // Create some additional random users for each merchant using factory
            User::factory(2)->create([
                'merchant_id' => $merchant->id,
            ])->each(function ($user) use ($roles) {
                // Randomly assign either Pilot or Ground Support role
                $user->assignRole($roles[array_rand($roles)]);
            });
        }
    }
}
