<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mnemonic>
 */
class MnemonicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'rules' => [$this->faker->sentence(), $this->faker->sentence()],
        ];
    }
}
