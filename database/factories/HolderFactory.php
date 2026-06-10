<?php

namespace Database\Factories;

use App\Models\Holder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Holder>
 */
class HolderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
        ];
    }
}
