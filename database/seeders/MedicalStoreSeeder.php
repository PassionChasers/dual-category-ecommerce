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
        // First, ensure there are 10 users with role 'medical_store'
        $users = User::where('role', 'medical_store')->get();

        if ($users->count() < 10) {
            $this->command->error('Not enough users with role medical_store. Please create at least 10 users.');
            return;
        }

        // Define 10 sample stores
        $stores = [
            ['Name' => 'City Pharmacy', 'LicenseNumber' => 'LIC-MED-001', 'GSTIN' => 'GSTIN-MED-001', 'PAN' => 'PAN-MED-001', 'IsActive' => true, 'IsFeatured' => true, 'OpenTime' => '08:00:00', 'CloseTime' => '21:00:00', 'RadiusKm' => 5.5, 'DeliveryFee' => 50.0, 'MinOrder' => 200.0, 'Latitude' => 26.4525, 'Longitude' => 87.2718, 'Priority' => 1, 'ImageUrl' => 'medicalstores/city_pharmacy.png'],
            ['Name' => 'Health Plus Store', 'LicenseNumber' => 'LIC-MED-002', 'GSTIN' => 'GSTIN-MED-002', 'PAN' => 'PAN-MED-002', 'IsActive' => true, 'IsFeatured' => false, 'OpenTime' => '09:00:00', 'CloseTime' => '20:00:00', 'RadiusKm' => 7.0, 'DeliveryFee' => 40.0, 'MinOrder' => 150.0, 'Latitude' => 26.4500, 'Longitude' => 87.2700, 'Priority' => 2, 'ImageUrl' => 'medicalstores/health_plus.png'],
            ['Name' => 'Wellness Pharmacy', 'LicenseNumber' => 'LIC-MED-003', 'GSTIN' => 'GSTIN-MED-003', 'PAN' => 'PAN-MED-003', 'IsActive' => true, 'IsFeatured' => true, 'OpenTime' => '07:30:00', 'CloseTime' => '22:00:00', 'RadiusKm' => 6.0, 'DeliveryFee' => 60.0, 'MinOrder' => 250.0, 'Latitude' => 26.4550, 'Longitude' => 87.2720, 'Priority' => 3, 'ImageUrl' => 'medicalstores/wellness.png'],
            ['Name' => 'Medicure Pharmacy', 'LicenseNumber' => 'LIC-MED-004', 'GSTIN' => 'GSTIN-MED-004', 'PAN' => 'PAN-MED-004', 'IsActive' => true, 'IsFeatured' => false, 'OpenTime' => '08:30:00', 'CloseTime' => '21:30:00', 'RadiusKm' => 4.5, 'DeliveryFee' => 55.0, 'MinOrder' => 180.0, 'Latitude' => 26.4530, 'Longitude' => 87.2730, 'Priority' => 2, 'ImageUrl' => 'medicalstores/medicure.png'],
            ['Name' => 'CarePlus Pharmacy', 'LicenseNumber' => 'LIC-MED-005', 'GSTIN' => 'GSTIN-MED-005', 'PAN' => 'PAN-MED-005', 'IsActive' => true, 'IsFeatured' => true, 'OpenTime' => '09:00:00', 'CloseTime' => '20:30:00', 'RadiusKm' => 5.0, 'DeliveryFee' => 45.0, 'MinOrder' => 160.0, 'Latitude' => 26.4560, 'Longitude' => 87.2740, 'Priority' => 1, 'ImageUrl' => 'medicalstores/careplus.png'],
            ['Name' => 'Healing Touch Pharmacy', 'LicenseNumber' => 'LIC-MED-006', 'GSTIN' => 'GSTIN-MED-006', 'PAN' => 'PAN-MED-006', 'IsActive' => true, 'IsFeatured' => false, 'OpenTime' => '07:00:00', 'CloseTime' => '21:00:00', 'RadiusKm' => 6.5, 'DeliveryFee' => 50.0, 'MinOrder' => 200.0, 'Latitude' => 26.4570, 'Longitude' => 87.2750, 'Priority' => 3, 'ImageUrl' => 'medicalstores/healing_touch.png'],
            ['Name' => 'Good Health Pharmacy', 'LicenseNumber' => 'LIC-MED-007', 'GSTIN' => 'GSTIN-MED-007', 'PAN' => 'PAN-MED-007', 'IsActive' => true, 'IsFeatured' => true, 'OpenTime' => '08:00:00', 'CloseTime' => '22:00:00', 'RadiusKm' => 5.5, 'DeliveryFee' => 60.0, 'MinOrder' => 220.0, 'Latitude' => 26.4580, 'Longitude' => 87.2760, 'Priority' => 2, 'ImageUrl' => 'medicalstores/good_health.png'],
            ['Name' => 'Family Care Pharmacy', 'LicenseNumber' => 'LIC-MED-008', 'GSTIN' => 'GSTIN-MED-008', 'PAN' => 'PAN-MED-008', 'IsActive' => true, 'IsFeatured' => false, 'OpenTime' => '07:30:00', 'CloseTime' => '21:30:00', 'RadiusKm' => 4.0, 'DeliveryFee' => 40.0, 'MinOrder' => 150.0, 'Latitude' => 26.4590, 'Longitude' => 87.2770, 'Priority' => 1, 'ImageUrl' => 'medicalstores/family_care.png'],
            ['Name' => 'Trust Pharmacy', 'LicenseNumber' => 'LIC-MED-009', 'GSTIN' => 'GSTIN-MED-009', 'PAN' => 'PAN-MED-009', 'IsActive' => true, 'IsFeatured' => true, 'OpenTime' => '08:00:00', 'CloseTime' => '21:00:00', 'RadiusKm' => 5.0, 'DeliveryFee' => 55.0, 'MinOrder' => 180.0, 'Latitude' => 26.4600, 'Longitude' => 87.2780, 'Priority' => 2, 'ImageUrl' => 'medicalstores/trust.png'],
            ['Name' => 'WellCare Pharmacy', 'LicenseNumber' => 'LIC-MED-010', 'GSTIN' => 'GSTIN-MED-010', 'PAN' => 'PAN-MED-010', 'IsActive' => true, 'IsFeatured' => false, 'OpenTime' => '09:00:00', 'CloseTime' => '20:00:00', 'RadiusKm' => 6.0, 'DeliveryFee' => 50.0, 'MinOrder' => 200.0, 'Latitude' => 26.4610, 'Longitude' => 87.2790, 'Priority' => 3, 'ImageUrl' => 'medicalstores/wellcare.png'],
        ];

        foreach ($stores as &$store) {
            $store['MedicalStoreId'] = (string) Str::uuid();
            $store['UserId'] = $users->random()->id; // Assign random medical_store user
            $store['Slug'] = Str::slug($store['Name']);
            $store['CreatedAt'] = Carbon::now();
            $store['created_at'] = now();
            $store['updated_at'] = now();
        }

        DB::table('medicalstores')->insert($stores);

        $this->command->info(count($stores) . ' medical stores seeded.');
    }
}


