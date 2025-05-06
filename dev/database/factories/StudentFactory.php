<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->numerify('9#########'),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'degree' => fake()->randomElement(['graduate', 'postgrad', 'doctorate']),
            'likes' => fake()->randomElement(['reading', 'writing', 'drawing', 'cooking', 'dancing', 'singing', 'other']),
            'address' => fake()->address(),
            'is_active' => fake()->boolean(),
        ];
    }
}
