<?php

use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(\Database\Seeders\SuperAdminSeeder::class);

        // Create test notifications
        $superAdmin = \App\Models\User::where('email', 'admin@smartblood.ph')->first();
        if ($superAdmin) {
            \App\Models\AppNotification::create([
                'user_id' => $superAdmin->id,
                'type' => 'info',
                'title' => 'Welcome to SmartBlood PH!',',
                'message' => 'Your account has been set up successfully. Start managing your blood bank operations.',
                'is_read' => false,
            ]);
            \App\Models\AppNotification::create([
                'user_id' => $superAdmin->id,
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => 'O- blood type is running low at Ormoc District Hospital.',
                'is_read' => false,
            ]);
        }
        
        $admin = User::create([
            'name' => 'System Administrator',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'fullname' => 'System Administrator',
            'email' => 'admin@smartblood.ph',
            'phone' => '+639171234567',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'status' => 'active',
            'is_registered' => true,
            'phone_verified' => true,
            'is_first_login' => false,
        ]);

        $ormocHospital = Facility::create([
            'name' => 'Ormoc District Hospital',
            'type' => 'hospital',
            'address' => 'Aviles St, Ormoc City',
            'city' => 'Ormoc City',
            'province' => 'Leyte',
            'latitude' => 11.0065,
            'longitude' => 124.6075,
            'contact_phone' => '+639171111111',
            'contact_email' => 'bloodbank@ormochospital.ph',
            'stock_status' => 'normal',
            'accepts_donations' => true,
        ]);

        $prcOrmoc = Facility::create([
            'name' => 'Philippine Red Cross - Ormoc Chapter',
            'type' => 'prc',
            'address' => 'Real St, Ormoc City',
            'city' => 'Ormoc City',
            'province' => 'Leyte',
            'latitude' => 11.0042,
            'longitude' => 124.6050,
            'contact_phone' => '+639172222222',
            'contact_email' => 'ormoc@redcross.org.ph',
            'stock_status' => 'normal',
            'accepts_donations' => true,
        ]);

        $facilityHead = User::create([
            'name' => 'Maria Santos',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'head@ormochospital.ph',
            'phone' => '+639173333333',
            'password' => Hash::make('Password123'),
            'role' => 'facility_head',
            'facility_id' => $ormocHospital->id,
            'is_registered' => true,
            'phone_verified' => true,
        ]);

        $ormocHospital->update(['head_user_id' => $facilityHead->id]);

        User::create([
            'name' => 'Juan Staff',
            'first_name' => 'Juan',
            'last_name' => 'Staff',
            'email' => 'staff@ormochospital.ph',
            'phone' => '+639174444444',
            'password' => Hash::make('Password123'),
            'role' => 'facility_staff',
            'facility_id' => $ormocHospital->id,
            'is_registered' => true,
            'phone_verified' => true,
        ]);

        $inventory = [
            ['blood_type' => 'O+', 'component_type' => 'whole_blood', 'quantity' => 12],
            ['blood_type' => 'O-', 'component_type' => 'packed_rbc', 'quantity' => 3],
            ['blood_type' => 'A+', 'component_type' => 'whole_blood', 'quantity' => 8],
            ['blood_type' => 'B+', 'component_type' => 'platelets', 'quantity' => 2],
            ['blood_type' => 'AB+', 'component_type' => 'plasma', 'quantity' => 5],
        ];

        foreach ($inventory as $item) {
            $collection = now()->subDays(rand(1, 10));
            $shelfLife = BloodInventory::shelfLifeDays($item['component_type']);
            BloodInventory::create([
                'facility_id' => $ormocHospital->id,
                'blood_type' => $item['blood_type'],
                'component_type' => $item['component_type'],
                'quantity' => $item['quantity'],
                'collection_date' => $collection,
                'expiry_date' => $collection->copy()->addDays($shelfLife),
                'freshness_flag' => $collection->diffInDays(now()) <= 14 ? 'fresh' : 'standard',
                'status' => 'available',
            ]);
        }

        BloodInventory::create([
            'facility_id' => $prcOrmoc->id,
            'blood_type' => 'O+',
            'component_type' => 'whole_blood',
            'quantity' => 25,
            'collection_date' => now()->subDays(3),
            'expiry_date' => now()->addDays(32),
            'freshness_flag' => 'fresh',
            'status' => 'available',
        ]);

        $donorUser = User::create([
            'name' => 'Pedro Donor',
            'first_name' => 'Pedro',
            'last_name' => 'Donor',
            'email' => 'donor@example.com',
            'phone' => '+639175555555',
            'password' => Hash::make('Password123'),
            'role' => 'user',
            'is_registered' => true,
            'phone_verified' => true,
        ]);

        Donor::create([
            'user_id' => $donorUser->id,
            'blood_type' => 'O+',
            'verification_status' => 'verified',
            'document_verified' => true,
            'donor_status' => 'available',
            'latitude' => 11.0080,
            'longitude' => 124.6100,
            'verified_by_facility_id' => $ormocHospital->id,
        ]);

        EmergencyRequest::create([
            'facility_id' => $ormocHospital->id,
            'requested_by' => $donorUser->id,
            'blood_type' => 'O-',
            'component_type' => 'packed_rbc',
            'quantity' => 2,
            'urgency' => 'critical',
            'status' => 'pending',
            'escalation_level' => 'prc',
            'notes' => 'Emergency cardiac surgery — critical O- shortage',
        ]);
    }
}
