<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MedicalStoreSeeder extends Seeder
{
    public function run(): void
    {
        // Get medical store user (role = medical_store)
        $medicalStoreUser = DB::table('users')
            ->where('role', 'medical_store')
            ->first();

        if (!$medicalStoreUser) {
            return; // safety check
        }

        DB::table('MedicalStores')->insert([
            [
                'MedicalStoreId' => (string) Str::uuid(),
                'UserId' => $medicalStoreUser->id, // UUID from users table

                'Name' => 'Sita Medical Store',
                'Slug' => 'sita-medical-store',
                'LicenseNumber' => 'LIC-MED-NEP-001',
                'GSTIN' => 'GSTIN-NEP-001',
                'PAN' => 'PAN-NEP-001',

                'IsActive' => true,
                'IsFeatured' => true,

                'OpenTime' => '08:00:00',
                'CloseTime' => '22:00:00',

                'RadiusKm' => 6.50,
                'DeliveryFee' => 40.00,
                'MinOrder' => 300.00,

                'Latitude' => 26.4525,
                'Longitude' => 87.2718,

                'Priority' => 1,
                'ImageUrl' => 'medicalstores/sita-medical.png',

                'CreatedAt' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
