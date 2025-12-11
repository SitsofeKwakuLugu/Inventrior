<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Inventrior Main Company (Optional but recommended)
        $company = Company::firstOrCreate(
            ['name' => 'Inventrior'],
            [
                'address' => 'Head Office',
                'email' => 'info@inventrior.com',
                'phone' => '0000000000',
            ]
        );

        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@inventrior.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('SuperAdmin123!'),
                'role' => 'superadmin',
                'company_id' => $company->id
            ]
        );
    }
}
