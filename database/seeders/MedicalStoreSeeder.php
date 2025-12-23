<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class MedicalStoreSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch users with role 'medical_store'
        $users = User::where('role', 'medical_store')->get();

        if ($users->isEmpty()) {
            $this->command->error('No users found with role medical_store. Please seed users first.');
            return;
        }

        $stores = [
            [
                'Name' => 'City Pharmacy',
                'LicenseNumber' => 'LIC-MED-001',
                'GSTIN' => 'GSTIN-MED-001',
                'PAN' => 'PAN-MED-001',
                'IsActive' => true,
                'IsFeatured' => true,
                'OpenTime' => '08:00:00',
                'CloseTime' => '21:00:00',
                'RadiusKm' => 5.50,
                'DeliveryFee' => 50.00,
                'MinOrder' => 200.00,
                'Latitude' => 26.4525,
                'Longitude' => 87.2718,
                'Priority' => 1,
                'ImageUrl' => 'medicalstores/city_pharmacy.png',
            ],
            [
                'Name' => 'Health Plus Store',
                'LicenseNumber' => 'LIC-MED-002',
                'GSTIN' => 'GSTIN-MED-002',
                'PAN' => 'PAN-MED-002',
                'IsActive' => true,
                'IsFeatured' => false,
                'OpenTime' => '09:00:00',
                'CloseTime' => '20:00:00',
                'RadiusKm' => 7.00,
                'DeliveryFee' => 40.00,
                'MinOrder' => 150.00,
                'Latitude' => 26.4500,
                'Longitude' => 87.2700,
                'Priority' => 2,
                'ImageUrl' => 'medicalstores/health_plus.png',
            ],
        ];

        foreach ($stores as &$store) {
            $store['MedicalStoreId'] = (string) Str::uuid();
            $store['UserId'] = $users->random()->id; // assign random medical_store user
            $store['Slug'] = Str::slug($store['Name']);
            $store['CreatedAt'] = Carbon::now();
            $store['created_at'] = now();
            $store['updated_at'] = now();
        }

        DB::table('medicalstores')->insert($stores);

        $this->command->info(count($stores) . ' medical stores seeded.');
    }
}
