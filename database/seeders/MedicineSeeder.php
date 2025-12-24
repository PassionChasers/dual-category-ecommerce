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
        $categories = DB::table('MedicineCategories')->pluck('MedicineCategoryId')->toArray();
        if (empty($categories)) {
            $this->command->error('No MedicineCategory found. Run MedicineCategorySeeder first.');
            return;
        }

        $medicalStores = DB::table('medicalstores')->pluck('MedicalStoreId')->toArray();
        if (empty($medicalStores)) {
            $this->command->error('No MedicalStore found. Run MedicalstoreSeeder first.');
            return;
        }

        $medicines = [
            [
                'Name' => 'Paracetamol 500mg',
                'GenericName' => 'Paracetamol',
                'BrandName' => 'Calpol',
                'Description' => 'Used to treat mild to moderate pain and fever.',
                'Price' => 30.00,
                'MRP' => 35.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'ABC Pharmaceuticals',
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => '10 Tablets per strip',
                'ImageUrl' => 'medicines/paracetamol.png',
                'AvgRating' => 4.5,
                'TotalReviews' => 120,
            ],
            [
                'Name' => 'Amoxicillin 250mg',
                'GenericName' => 'Amoxicillin',
                'BrandName' => 'Amoxil',
                'Description' => 'Antibiotic used to treat bacterial infections.',
                'Price' => 120.00,
                'MRP' => 140.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'XYZ Pharma',
                'DosageForm' => 'Capsule',
                'Strength' => '250mg',
                'Packaging' => '10 Capsules',
                'ImageUrl' => 'medicines/amoxicillin.png',
                'AvgRating' => 4.2,
                'TotalReviews' => 85,
            ],
            [
                'Name' => 'Cough Syrup',
                'GenericName' => 'Dextromethorphan',
                'BrandName' => 'Benylin',
                'Description' => 'Relieves cough and throat irritation.',
                'Price' => 90.00,
                'MRP' => 110.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'HealthCare Ltd',
                'DosageForm' => 'Syrup',
                'Strength' => '10mg/5ml',
                'Packaging' => '100ml Bottle',
                'ImageUrl' => 'medicines/cough_syrup.png',
                'AvgRating' => 4.0,
                'TotalReviews' => 60,
            ],
            [
                'Name' => 'Ibuprofen 400mg',
                'GenericName' => 'Ibuprofen',
                'BrandName' => 'Brufen',
                'Description' => 'Pain reliever and anti-inflammatory.',
                'Price' => 50.00,
                'MRP' => 60.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'Global Pharma',
                'DosageForm' => 'Tablet',
                'Strength' => '400mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/ibuprofen.png',
                'AvgRating' => 4.3,
                'TotalReviews' => 90,
            ],
            [
                'Name' => 'Vitamin C 500mg',
                'GenericName' => 'Ascorbic Acid',
                'BrandName' => 'Cevit',
                'Description' => 'Boosts immunity and overall health.',
                'Price' => 25.00,
                'MRP' => 30.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'NutriCare',
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/vitamin_c.png',
                'AvgRating' => 4.6,
                'TotalReviews' => 150,
            ],
            [
                'Name' => 'Metformin 500mg',
                'GenericName' => 'Metformin',
                'BrandName' => 'Glucophage',
                'Description' => 'Used to treat type 2 diabetes.',
                'Price' => 80.00,
                'MRP' => 100.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'DiabCare',
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/metformin.png',
                'AvgRating' => 4.4,
                'TotalReviews' => 110,
            ],
            [
                'Name' => 'Cetirizine 10mg',
                'GenericName' => 'Cetirizine',
                'BrandName' => 'Cetzine',
                'Description' => 'Relieves allergy symptoms.',
                'Price' => 40.00,
                'MRP' => 50.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'AllergyCare',
                'DosageForm' => 'Tablet',
                'Strength' => '10mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/cetirizine.png',
                'AvgRating' => 4.1,
                'TotalReviews' => 70,
            ],
            [
                'Name' => 'Ranitidine 150mg',
                'GenericName' => 'Ranitidine',
                'BrandName' => 'Zantac',
                'Description' => 'Reduces stomach acid and treats ulcers.',
                'Price' => 60.00,
                'MRP' => 75.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'StomachCare Ltd',
                'DosageForm' => 'Tablet',
                'Strength' => '150mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/ranitidine.png',
                'AvgRating' => 4.0,
                'TotalReviews' => 65,
            ],
            [
                'Name' => 'Aspirin 75mg',
                'GenericName' => 'Aspirin',
                'BrandName' => 'Disprin',
                'Description' => 'Used for pain relief and heart protection.',
                'Price' => 35.00,
                'MRP' => 40.00,
                'PrescriptionRequired' => false,
                'Manufacturer' => 'CardioPharma',
                'DosageForm' => 'Tablet',
                'Strength' => '75mg',
                'Packaging' => '10 Tablets',
                'ImageUrl' => 'medicines/aspirin.png',
                'AvgRating' => 4.2,
                'TotalReviews' => 80,
            ],
            [
                'Name' => 'Azithromycin 500mg',
                'GenericName' => 'Azithromycin',
                'BrandName' => 'Zithromax',
                'Description' => 'Antibiotic used to treat infections.',
                'Price' => 150.00,
                'MRP' => 180.00,
                'PrescriptionRequired' => true,
                'Manufacturer' => 'InfectoPharma',
                'DosageForm' => 'Tablet',
                'Strength' => '500mg',
                'Packaging' => '3 Tablets',
                'ImageUrl' => 'medicines/azithromycin.png',
                'AvgRating' => 4.3,
                'TotalReviews' => 95,
            ],
        ];

        foreach ($medicines as $med) {
            DB::table('Medicines')->insert([
                'MedicineId' => (string) Str::uuid(),
                'MedicalStoreId' => $medicalStores[array_rand($medicalStores)],
                'MedicineCategoryId' => $categories[array_rand($categories)],
                'Name' => $med['Name'],
                'GenericName' => $med['GenericName'],
                'BrandName' => $med['BrandName'],
                'Description' => $med['Description'],
                'Price' => $med['Price'],
                'MRP' => $med['MRP'],
                'PrescriptionRequired' => $med['PrescriptionRequired'],
                'Manufacturer' => $med['Manufacturer'],
                'ExpiryDate' => Carbon::now()->addYears(rand(1, 3))->toDateString(),
                'DosageForm' => $med['DosageForm'],
                'Strength' => $med['Strength'],
                'Packaging' => $med['Packaging'],
                'ImageUrl' => $med['ImageUrl'],
                'IsActive' => true,
                'AvgRating' => $med['AvgRating'],
                'TotalReviews' => $med['TotalReviews'],
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ]);
        }
    }

}
