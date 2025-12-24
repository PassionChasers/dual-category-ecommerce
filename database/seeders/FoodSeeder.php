<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Get all restaurants
        $restaurants = DB::table('restaurants')->get();
        if ($restaurants->isEmpty()) {
            $this->command->error('No restaurants found. Run RestaurantSeeder first.');
            return;
        }

        // Get all food categories
        $categories = DB::table('FoodCategories')->get();
        if ($categories->isEmpty()) {
            $this->command->error('No food categories found. Run FoodCategorySeeder first.');
            return;
        }

        $foods = [
            ['Pizza Margherita', 250],
            ['Cheese Burger', 150],
            ['Chicken Sandwich', 180],
            ['Spaghetti Pasta', 220],
            ['Caesar Salad', 200],
            ['Chocolate Cake', 120],
            ['Coke', 50],
            ['Paneer Butter Masala', 230],
            ['Fried Rice', 210],
            ['Momo', 100],
        ];

        $foodData = [];

        foreach ($foods as $index => $food) {
            $restaurant = $restaurants->random(); // pick a random restaurant
            $category = $categories[$index % $categories->count()]; // rotate categories

            $foodData[] = [
                'FoodId' => (string) Str::uuid(),
                'RestaurantId' => $restaurant->RestaurantId,
                'FoodCategoryId' => $category->FoodCategoryId,
                'Name' => $food[0],
                'Description' => $food[0] . ' delicious',
                'Price' => $food[1],
                'MRP' => $food[1] + 20,
                'ImageUrl' => 'foods/' . Str::slug($food[0]) . '.png',
                'IsActive' => true,
                'AvgRating' => rand(3, 5),
                'TotalReviews' => rand(0, 100),
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ];
        }

        DB::table('Foods')->insert($foodData);

        $this->command->info(count($foodData) . ' foods seeded across multiple restaurants.');
    }
}

