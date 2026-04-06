<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'status'      => $this->faker->randomElement(['todo', 'in_progress', 'done']),
            'priority'    => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date'    => $this->faker->optional()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }
}
