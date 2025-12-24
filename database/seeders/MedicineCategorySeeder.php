<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MedicineCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('MedicineCategories')->insert([
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Pain Relief',
                'Description' => 'Medicines used to relieve pain',
                'IsActive' => true,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Antibiotics',
                'Description' => 'Medicines for bacterial infection',
                'IsActive' => true,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Cold & Flu',
                'Description' => 'Medicines for cold, flu, and cough',
                'IsActive' => false,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Digestive Health',
                'Description' => 'Medicines for digestion, acidity, and stomach issues',
                'IsActive' => false,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Diabetes Care',
                'Description' => 'Medicines to manage blood sugar levels',
                'IsActive' => true,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Heart & Blood Pressure',
                'Description' => 'Medicines for heart health and blood pressure control',
                'IsActive' => false,
                'CreatedAt' => Carbon::now(),
            ],
            [
                'MedicineCategoryId' => (string) Str::uuid(),
                'Name' => 'Skin Care',
                'Description' => 'Medicines for skin infections and allergies',
                'IsActive' => true,
                'CreatedAt' => Carbon::now(),
            ],
        ]);

    }
}
