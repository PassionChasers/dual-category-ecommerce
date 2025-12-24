<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        // 2 Admins
        $admins = [
            ['name' => 'Admin', 'email' => 'admin@gmail.com'],
            ['name' => 'Admin2', 'email' => 'admin2@gmail.com'],
        ];
        foreach ($admins as $admin) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Management',
                'role' => 'admin',
                'contact_number' => '981100' . rand(1000, 9999),
                'address' => 'Biratnagar',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 10 Medical Store Users
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => "MedicalStoreUser $i",
                'email' => "medstore$i@gmail.com",
                'password' => Hash::make('password'),
                'designation' => 'Manager',
                'department' => 'Medicine',
                'role' => 'medical_store',
                'contact_number' => '981200' . rand(1000, 9999),
                'address' => 'Biratnagar',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 10 Restaurant Users
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => "RestaurantUser $i",
                'email' => "restaurant$i@gmail.com",
                'password' => Hash::make('password'),
                'designation' => 'Manager',
                'department' => 'Food',
                'role' => 'restaurant',
                'contact_number' => '981300' . rand(1000, 9999),
                'address' => 'Biratnagar',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 20 Customers
        for ($i = 1; $i <= 20; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => "Customer $i",
                'email' => "customer$i@gmail.com",
                'password' => Hash::make('password'),
                'designation' => '',
                'department' => '',
                'role' => 'customer',
                'contact_number' => '981400' . rand(1000, 9999),
                'address' => 'Biratnagar',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5 Delivery Personnel
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => "DeliveryMan $i",
                'email' => "delivery$i@gmail.com",
                'password' => Hash::make('password'),
                'designation' => 'Delivery',
                'department' => 'Logistics',
                'role' => 'delivery',
                'contact_number' => '981500' . rand(1000, 9999),
                'address' => 'Biratnagar',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all users
        DB::table('users')->insert($users);

        $this->command->info('Users seeded: ' . count($users));
    }
}
