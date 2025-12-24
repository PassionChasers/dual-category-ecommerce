<?php

namespace Database\Seeders;

// use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(MedicalStoreSeeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(MedicineCategorySeeder::class);
        $this->call(MedicineSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(OrderItemsSeeder::class);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
