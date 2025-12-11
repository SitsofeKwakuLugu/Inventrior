<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Inventrior Main Company with UUID
        $company = Company::create([
            'id' => (string) Str::uuid(),
            'name' => 'Inventrior',
            'address' => 'Head Office',
            'email' => 'info@inventrior.com',
            'phone' => '0000000000',
            'verified' => true,
        ]);

        // Create Super Admin
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@inventrior.com',
            'password' => Hash::make('SuperAdmin123!'),
            'role' => 'super-admin',
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);
    }
}
