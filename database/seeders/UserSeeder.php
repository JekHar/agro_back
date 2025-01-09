<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            if ($merchant->merchant_type === 'tenant') {
                $tenant = User::create([
                    'name' => "Tenant {$merchant->business_name}",
                    'email' => "tenant.{$merchant->id}@" . strtolower(str_replace(' ', '', $merchant->business_name)) . '.com',
                    'password' => Hash::make('password'),
                    'merchant_id' => $merchant->id,
                ]);
                $tenant->assignRole('Tenant');
            }

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

            User::factory(2)->create([
                'merchant_id' => $merchant->id,
            ])->each(function ($user) use ($roles) {
                $user->assignRole($roles[array_rand($roles)]);
            });
        }
    }
}
