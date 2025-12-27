<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $admin = User::where('role', 'admin')->first();

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([
                'pending', 'in_progress', 'completed'
            ]),
            'priority' => $this->faker->randomElement([
                'low', 'medium', 'high'
            ]),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),

            // 👇 ONLY admin creates tasks
            'user_id' => $admin->id,
        ];
    }
}
