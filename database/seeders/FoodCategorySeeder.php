<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pizza', 'Burger', 'Sandwich', 'Pasta', 'Salad',
            'Desserts', 'Beverages', 'Indian', 'Chinese', 'Nepali'
        ];

        $data = [];

        foreach ($categories as $name) {
            $data[] = [
                'FoodCategoryId' => (string) Str::uuid(),
                'Name' => $name,
                'Description' => $name . ' items',
                'IsActive' => true,
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ];
        }

        DB::table('FoodCategories')->insert($data);

        $this->command->info(count($data) . ' food categories seeded.');
    }
}

