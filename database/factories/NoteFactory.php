<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'color' => $this->faker->randomElement(['#ffffff', '#fee2e2', '#fef3c7', '#dcfce7', '#dbeafe', '#e0e7ff', '#fce7f3']),
            'is_pinned' => $this->faker->boolean(20),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
