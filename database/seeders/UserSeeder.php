<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use App\Types\MerchantType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            if ($merchant->merchant_type === MerchantType::TENANT) {
                $tenant = User::create([
                    'name' => "Tenant {$merchant->business_name}",
                    'email' => "tenant.{$merchant->id}@" . strtolower(str_replace(' ', '', $merchant->business_name)) . '.com',
                    'password' => Hash::make('password'),
                    'merchant_id' => $merchant->id,
                    'email_verified_at' => now(),
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
                    'email_verified_at' => now(),
                ]);

                $user->assignRole($role);
            }
        }
    }
}
