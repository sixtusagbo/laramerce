<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = \App\Models\User::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($users),
            'checked_out' => $this->faker->boolean,
            'checked_out_at' => $this->faker->dateTime,
        ];
    }
}
