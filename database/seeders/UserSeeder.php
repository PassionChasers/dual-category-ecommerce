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
        DB::table('users')->insert([
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Food',
                'role' => 'admin',
                'contact_number' =>'9811349989',
                'address' => 'Duhabi',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Rabin Chaudhary',
                'email' => 'rabin@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Medicine',
                'role' => 'sub_admin',
                'contact_number' =>'9816321861',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Ram Chaudhary',
                'email' => 'ram@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Food',
                'role' => 'restaurant',
                'contact_number' =>'9816321899',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Sita Chaudhary',
                'email' => 'sita@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Medicine',
                'role' => 'medical_store',
                'contact_number' =>'9816321811',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Madhu Yadav',
                'email' => 'madhu@gmail.com',
                'password' => Hash::make('password'),
                'designation' => '',
                'department' => '',
                'role' => 'customer',
                'contact_number' =>'9816322222',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Sonu Chaudhary',
                'email' => 'sonu@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Medicine',
                'role' => 'customer',
                'contact_number' =>'9816321333',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
               [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Sunil Chaudhary',
                'email' => 'sunil@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Medicine',
                'role' => 'delivery',
                'contact_number' =>'9816321444',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(), // UUID primary key
                'name' => 'Saurav Nepal',
                'email' => 'saurav@gmail.com',
                'password' => Hash::make('password'),
                'designation' => 'CEO',
                'department' => 'Medicine',
                'role' => 'delivery',
                'contact_number' =>'9816321800',
                'address' => 'BRT',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
