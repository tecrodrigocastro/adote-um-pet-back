<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Pet;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['dog', 'cat', 'rabbit', 'bird']),
            'gender' => $this->faker->randomElement(['female', 'male', 'unknown']),
            'size' => $this->faker->randomElement(['small', 'medium', 'large', 'unknown']),
            'birth_date' => $this->faker->date(),
            'breed' => $this->faker->word,
            'color' => $this->faker->colorName,
            'address' => $this->faker->address,
            'description' => $this->faker->sentence,
            'photos' => $this->faker->imageUrl(640, 480),
        ];
    }
}
