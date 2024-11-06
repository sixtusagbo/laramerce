<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // RTFM - https://laravel.com/docs/11.x/seeding
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Sixtus Agbo',
            'email' => 'miracleagbosixtus@gmail.com',
            'role' => 'admin',
        ]);

        $this->call([
            ProductSeeder::class,
            CartSeeder::class,
            CartProductSeeder::class,
        ]);
    }
}
