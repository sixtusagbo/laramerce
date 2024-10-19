<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(10)->create();

        // if in production, use the following code
        if (app()->environment('production')) {
            Product::factory()->create([
                'name' => 'Test Product Production',
                'description' => 'This is a test product',
                'price' => 9.99,
            ]);
        }

        // if in development, use the following code
        if (app()->environment('local')) {
            Product::factory()->create([
                'name' => 'Test Product Development',
                'description' => 'This is a test product',
                'price' => 9.99,
            ]);
        }
    }
}
