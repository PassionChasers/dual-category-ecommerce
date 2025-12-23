<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // Get a category ID to associate medicines
        $category = DB::table('MedicineCategories')->first();
        if (!$category) {
            $this->command->error('No MedicineCategory found. Run MedicineCategorySeeder first.');
            return;
        }

         // Get a medicalstore ID to associate medicines
        $medicalstore = DB::table('medicalstores')->first();
        if (!$medicalstore) {
            $this->command->error('No MedicalStore found. Run Medicalstoreseeder first.');
            return;
        }

        DB::table('Medicines')->insert([
            [
                'MedicineId' => (string) Str::uuid(),
                'MedicalStoreId' => $medicalstore->MedicalStoreId,
                'MedicineCategoryId' => $category->MedicineCategoryId,
                'Name' => 'Paracetamol 500mg',
                'GenericName' => 'Paracetamol',
                'BrandName' => 'Calpol',
                'Description' => 'Used to treat mild to moderate pain and fever.',
                'Price' => 30.00,
                'MRP' => 35.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'ABC Pharmaceuticals',
                'ExpiryDate' => Carbon::now()->addYears(2)->toDateString(),
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => '10 Tablets per strip',
                'ImageUrl' => 'medicines/paracetamol.png',
                'IsActive' => true,
                'AvgRating' => 4.5,
                'TotalReviews' => 120,
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ],
            [
                'MedicineId' => (string) Str::uuid(),
                'MedicalStoreId' => $medicalstore->MedicalStoreId,
                'MedicineCategoryId' => $category->MedicineCategoryId,
                'Name' => 'Amoxicillin 250mg',
                'GenericName' => 'Amoxicillin',
                'BrandName' => 'Amoxil',
                'Description' => 'Antibiotic used to treat bacterial infections.',
                'Price' => 120.00,
                'MRP' => 140.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'XYZ Pharma',
                'ExpiryDate' => Carbon::now()->addYear()->toDateString(),
                'DosageForm' => 'Capsule',
                'Strength' => '250mg',
                'Packaging' => '10 Capsules',
                'ImageUrl' => 'medicines/amoxicillin.png',
                'IsActive' => true,
                'AvgRating' => 4.2,
                'TotalReviews' => 85,
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ],
        ]);
    }
}
