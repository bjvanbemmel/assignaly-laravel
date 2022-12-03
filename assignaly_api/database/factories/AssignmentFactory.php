<?php

namespace Database\Factories;

use App\Enums\AssignmentStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'owner_id' => User::where('name', '=', 'Test User')->first()->id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'due_at' => fake()->dateTimeThisYear(),
            'finished_at' => [ null, now() ][ rand(0, 1) ],
            'review' => fake()->numberBetween(1, 10),
            'feedback' => fake()->realText(),
            'status' => [
                AssignmentStatusEnum::OPEN,
                AssignmentStatusEnum::CLOSED,
                AssignmentStatusEnum::IN_REVIEW
            ][ rand(0, 2) ],
        ];
    }
}
