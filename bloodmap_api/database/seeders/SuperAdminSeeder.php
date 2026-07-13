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
        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@bloodmap.ph');
        $name = env('SUPER_ADMIN_NAME', 'Super Admin');
        $fullname = env('SUPER_ADMIN_FULLNAME', 'Blood Map PH Super Admin');
        $password = env('SUPER_ADMIN_PASSWORD');
        
        if (! $password) {
            $this->command->warn('SUPER_ADMIN_PASSWORD not set in .env, using default temporary password. Please change it immediately!');
            $password = 'ChangeMe123!';
        }
        
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'fullname' => $fullname,
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'status' => 'active',
                'is_first_login' => true,
                'is_registered' => true,
                'two_factor_enabled' => false,
            ]
        );
        
        $this->command->info("Super admin account created for {$email}");
    }
}
