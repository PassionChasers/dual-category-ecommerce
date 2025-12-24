<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\MedicalStore;

class OrdersSeeder extends Seeder
{
   public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $restaurants = Restaurant::pluck('RestaurantId')->toArray();
        $medicalStores = MedicalStore::pluck('MedicalStoreId')->toArray();

        // Shuffle arrays to ensure uniqueness
        shuffle($users);
        shuffle($restaurants);
        shuffle($medicalStores);

        // Seed 10 FOOD orders
        foreach (range(0, 9) as $i) {
            Order::create([
                'id' => Str::uuid(),
                'user_id' => $users[$i % count($users)], // unique user per order
                'order_type' => 'food',
                'restaurant_id' => $restaurants[$i % count($restaurants)],
                'medicalstore_id' => null,

                'order_number' => 'ORD-FOOD-' . now()->timestamp . '-' . mt_rand(1000,9999),
                'subtotal' => rand(200, 1000),
                'delivery_charge' => rand(20, 100),
                'tax' => rand(10, 50),
                'discount' => rand(0, 50),
                'total_amount' => rand(250, 1100),

                'payment_method' => ['cod', 'esewa', 'khalti'][array_rand(['cod','esewa','khalti'])],
                'payment_status' => ['pending', 'paid'][array_rand(['pending','paid'])],
                'order_status' => ['pending', 'accepted', 'preparing', 'packed', 'out_for_delivery', 'delivered'][array_rand(['pending','accepted','preparing','packed','out_for_delivery','delivered'])],

                'delivery_address' => 'Some Address ' . ($i+1),
                'notes' => 'Deliver carefully',

                'prescription_image' => null,
                'prescription_verified' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed 10 MEDICINE orders
        foreach (range(0, 9) as $i) {
            Order::create([
                'id' => Str::uuid(),
                'user_id' => $users[$i % count($users)],
                'order_type' => 'medicine',
                'restaurant_id' => null,
                'medicalstore_id' => $medicalStores[$i % count($medicalStores)],

                'order_number' => 'ORD-MED-' . now()->timestamp . '-' . mt_rand(1000,9999),
                'subtotal' => rand(300, 1500),
                'delivery_charge' => rand(20, 100),
                'tax' => rand(20, 80),
                'discount' => rand(0, 100),
                'total_amount' => rand(350, 1600),

                'payment_method' => ['cod', 'esewa', 'khalti'][array_rand(['cod','esewa','khalti'])],
                'payment_status' => ['pending', 'paid'][array_rand(['pending','paid'])],
                'order_status' => ['pending', 'accepted', 'preparing', 'packed', 'out_for_delivery', 'delivered'][array_rand(['pending','accepted','preparing','packed','out_for_delivery','delivered'])],

                'delivery_address' => 'Some Address ' . ($i+1),
                'notes' => 'Handle with care',

                'prescription_image' => 'prescriptions/sample' . ($i+1) . '.jpg',
                'prescription_verified' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

}
