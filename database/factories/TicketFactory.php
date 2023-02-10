<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => fake()->uuid(),
            'user_name' => fake()->name(),
            'selected_numbers' => fake()->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 6),
            'machine_numbers' => fake()->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 6),
            'machine_draw_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'is_active' => fake()->boolean(),
            'is_winner' => fake()->boolean(),
        ];
    }
}
