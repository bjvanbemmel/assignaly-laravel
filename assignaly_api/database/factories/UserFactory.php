<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected string $user = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('P4$$W0Rd'),
            'institute_id' => Institute::first()->id,
            'role_id' => Role::find(rand(1, 3))->id,
            'settings' => Collection::make([ 'profile_icon_color' => $this->getRandomColor(), ]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function getRandomColor(): string
    {
        return ['red', 'blue', 'green', 'orange', 'purple', 'yellow', 'lime', 'cyan', 'amber', 'emerald', 'violet', 'fuchsia', 'rose' ][rand(0, 12)];
    }
}
