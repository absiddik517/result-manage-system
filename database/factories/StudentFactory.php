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
    public function definition()
    {
        return [
            'class_id' => $this->faker->numberBetween(1,3),
            'name' => $this->faker->name(),
            'roll' => $this->faker->randomDigitNot(0),
            'address' => $this->faker->address(),
            'phone' => $this->faker->randomNumber(5, true),
            'father_name' => $this->faker->name(),
            'mother_name' => $this->faker->name(),
        ];
    }
}
