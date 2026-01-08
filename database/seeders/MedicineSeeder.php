<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MedicinesSeeder extends Seeder
{
    public function run(): void
    {
        // Get all medicine category IDs from the table
        $categoryIds = DB::table('MedicineCategories')->pluck('MedicineCategoryId')->toArray();

        if (empty($categoryIds)) {
            $this->command->info('No medicine categories found. Please seed MedicineCategories first.');
            return;
        }

        // Example medicines to seed
        $medicines = [
            [
                'MedicineId' => (string) Str::uuid(),
                'MedicineCategoryId' => $categoryIds[array_rand($categoryIds)],
                'Name' => 'Paracetamol',
                'GenericName' => 'Paracetamol',
                'BrandName' => 'Tylenol',
                'Description' => 'Pain reliever and fever reducer',
                'Price' => 2.50,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'Acme Pharma',
                'ExpiryDate' => now()->addYears(2),
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => 'Box of 10 tablets',
                'AvgRating' => 4.5,
                'TotalReviews' => 10,
                'ImageUrl' => null,
                'IsActive' => true,
                'CreatedAt' => now(),
                'UpdatedAt' => now(),
            ],
            [
                'MedicineId' => (string) Str::uuid(),
                'MedicineCategoryId' => $categoryIds[array_rand($categoryIds)],
                'Name' => 'Amoxicillin',
                'GenericName' => 'Amoxicillin',
                'BrandName' => 'Amoxil',
                'Description' => 'Antibiotic used to treat infections',
                'Price' => 5.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'Acme Pharma',
                'ExpiryDate' => now()->addYears(2),
                'DosageForm' => 'Capsule',
                'Strength' => '250mg',
                'Packaging' => 'Box of 10 capsules',
                'AvgRating' => 4.2,
                'TotalReviews' => 5,
                'ImageUrl' => null,
                'IsActive' => true,
                'CreatedAt' => now(),
                'UpdatedAt' => now(),
            ],
        ];

        // Insert into database
        DB::table('Medicines')->insert($medicines);

        $this->command->info('Medicines seeded successfully.');
    }
}
