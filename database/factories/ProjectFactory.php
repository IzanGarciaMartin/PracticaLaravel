<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'deadline' => fake()->dateTimeBetween('now', '+6 months'),
            'status' => fake()->randomElement([
                'pending',
                'in_progress',
                'completed',
            ]),
        ];
    }
}