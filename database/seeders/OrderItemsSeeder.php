<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodProducts = [
            ['name' => 'Burger', 'price' => 100],
            ['name' => 'Fries', 'price' => 50],
            ['name' => 'Pizza', 'price' => 250],
            ['name' => 'Sandwich', 'price' => 120],
            ['name' => 'Coke', 'price' => 30],
        ];

        $medicineProducts = [
            ['name' => 'Paracetamol 500mg', 'price' => 30],
            ['name' => 'Vitamin C', 'price' => 40],
            ['name' => 'Amoxicillin 250mg', 'price' => 120],
            ['name' => 'Ibuprofen 200mg', 'price' => 50],
            ['name' => 'Antacid Tablets', 'price' => 25],
        ];

        // Get all orders
        $orders = Order::all();

        foreach ($orders as $order) {
            if ($order->order_type === 'food') {
                // Add 2-3 random food items per order
                $items = collect($foodProducts)->random(rand(2, 3));

                foreach ($items as $item) {
                    $quantity = rand(1, 3);
                    OrderItem::create([
                        'id' => Str::uuid(),
                        'order_id' => $order->id,
                        'product_name' => $item['name'],
                        'quantity' => $quantity,
                        'price' => $item['price'],
                        'total' => $item['price'] * $quantity,
                    ]);
                }
            } else { // medicine order
                // Add 2-3 random medicine items per order
                $items = collect($medicineProducts)->random(rand(2, 3));

                foreach ($items as $item) {
                    $quantity = rand(1, 5);
                    OrderItem::create([
                        'id' => Str::uuid(),
                        'order_id' => $order->id,
                        'product_name' => $item['name'],
                        'quantity' => $quantity,
                        'price' => $item['price'],
                        'total' => $item['price'] * $quantity,
                    ]);
                }
            }
        }
    }

}
