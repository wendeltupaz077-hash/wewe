<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smartblood.ph'],
            [
                'name' => 'Super Admin',
                'fullname' => 'Blood Map PH Super Admin',
                'password' => Hash::make('Password123!'),
                'role' => 'super_admin',
                'status' => 'active',
                'is_first_login' => true,
                'is_registered' => true,
            ]
        );
    }
}
