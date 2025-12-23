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
        // Get restaurant user (role = restaurant)
        $restaurantUser = DB::table('users')
            ->where('role', 'restaurant')
            ->first();

        if (!$restaurantUser) {
            return; // safety check
        }

        DB::table('restaurants')->insert([
            [
                'RestaurantId' => (string) Str::uuid(),
                'UserId' => $restaurantUser->id, // UUID from users table

                'Name' => 'Gopal Fast Food',
                'Slug' => 'gopal-fast-food',
                'LicenseNumber' => 'LIC-RES-NEP-001',
                'GSTIN' => 'GSTIN-NEP-002',
                'PAN' => 'PAN-NEP-002',

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
                'ImageUrl' => 'restaurants/gopal.png',

                'CreatedAt' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
