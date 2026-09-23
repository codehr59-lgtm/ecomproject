<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Catalog data: categories, brands, products
        $this->call(CatalogSeeder::class);

        // Default test user
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Admin user
        $this->call(AdminSeeder::class);

        // Combo offers & Grocery demo catalog
        $this->call(ComboSeeder::class);
        $this->call(GroceryDemoSeeder::class);
    }
}
