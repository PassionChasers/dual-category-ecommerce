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
        ]);
    }
}
