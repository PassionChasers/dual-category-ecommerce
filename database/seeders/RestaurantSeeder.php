<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all restaurant users
        $restaurantUsers = DB::table('users')->where('role', 'restaurant')->get();

        if ($restaurantUsers->isEmpty()) {
            $this->command->error('No restaurant users found. Seed users first.');
            return;
        }

        $restaurantsData = [
            [
                'Name' => 'Gopal Fast Food',
                'Slug' => 'gopal-fast-food',
                'LicenseNumber' => 'LIC-RES-NEP-001',
                'GSTIN' => 'GSTIN-NEP-001',
                'PAN' => 'PAN-NEP-001',
                'IsActive' => true,
                'IsFeatured' => true,
                'OpenTime' => '08:00:00',
                'CloseTime' => '22:00:00',
                'RadiusKm' => 6.5,
                'DeliveryFee' => 40.00,
                'MinOrder' => 300.00,
                'Latitude' => 26.4525,
                'Longitude' => 87.2718,
                'Priority' => 1,
                'ImageUrl' => 'restaurants/gopal.png',
            ],
            [
                'Name' => 'Darjeeling MoMo',
                'Slug' => 'darjeeling-momo',
                'LicenseNumber' => 'LIC-RES-NEP-002',
                'GSTIN' => 'GSTIN-NEP-002',
                'PAN' => 'PAN-NEP-002',
                'IsActive' => true,
                'IsFeatured' => true,
                'OpenTime' => '09:00:00',
                'CloseTime' => '21:00:00',
                'RadiusKm' => 5.5,
                'DeliveryFee' => 35.00,
                'MinOrder' => 250.00,
                'Latitude' => 26.4510,
                'Longitude' => 87.2720,
                'Priority' => 2,
                'ImageUrl' => 'restaurants/darjeeling.png',
            ],
            [
                'Name' => 'Everest Snacks',
                'Slug' => 'everest-snacks',
                'LicenseNumber' => 'LIC-RES-NEP-003',
                'GSTIN' => 'GSTIN-NEP-003',
                'PAN' => 'PAN-NEP-003',
                'IsActive' => true,
                'IsFeatured' => false,
                'OpenTime' => '10:00:00',
                'CloseTime' => '20:00:00',
                'RadiusKm' => 4.5,
                'DeliveryFee' => 30.00,
                'MinOrder' => 200.00,
                'Latitude' => 26.4505,
                'Longitude' => 87.2730,
                'Priority' => 3,
                'ImageUrl' => 'restaurants/everest.png',
            ],
            [
                'Name' => 'Mountain Pizza',
                'Slug' => 'mountain-pizza',
                'LicenseNumber' => 'LIC-RES-NEP-004',
                'GSTIN' => 'GSTIN-NEP-004',
                'PAN' => 'PAN-NEP-004',
                'IsActive' => false,
                'IsFeatured' => true,
                'OpenTime' => '11:00:00',
                'CloseTime' => '23:00:00',
                'RadiusKm' => 5.0,
                'DeliveryFee' => 50.00,
                'MinOrder' => 350.00,
                'Latitude' => 26.4520,
                'Longitude' => 87.2740,
                'Priority' => 4,
                'ImageUrl' => 'restaurants/mountain.png',
            ],
            [
                'Name' => 'Pokhara Foods',
                'Slug' => 'pokhara-foods',
                'LicenseNumber' => 'LIC-RES-NEP-005',
                'GSTIN' => 'GSTIN-NEP-005',
                'PAN' => 'PAN-NEP-005',
                'IsActive' => true,
                'IsFeatured' => false,
                'OpenTime' => '07:00:00',
                'CloseTime' => '22:00:00',
                'RadiusKm' => 6.0,
                'DeliveryFee' => 45.00,
                'MinOrder' => 280.00,
                'Latitude' => 26.4530,
                'Longitude' => 87.2750,
                'Priority' => 5,
                'ImageUrl' => 'restaurants/pokhara.png',
            ],
        ];

        // Assign each restaurant a unique restaurant user
        foreach ($restaurantsData as $index => &$restaurant) {
            $restaurant['RestaurantId'] = (string) Str::uuid();
            $restaurant['UserId'] = $restaurantUsers[$index % $restaurantUsers->count()]->id;
            $restaurant['CreatedAt'] = Carbon::now();
            $restaurant['created_at'] = now();
            $restaurant['updated_at'] = now();
        }

        DB::table('restaurants')->insert($restaurantsData);

        $this->command->info('5 restaurants seeded successfully.');
    }
}
