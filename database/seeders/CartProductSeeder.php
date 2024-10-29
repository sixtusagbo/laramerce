<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = \App\Models\Cart::all();
        $products = \App\Models\Product::all();

        $carts->each(function ($cart) use ($products) {
            $cart->products()->attach(
                $products->random(rand(1, 5))->pluck('id')->toArray(),
                ['quantity' => rand(1, 5)],
            );
        });
    }
}
