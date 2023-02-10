<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DetailProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dp_id' => $this->faker->unique()->numberBetween(1, 200),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL'], 4),
            'stock' => $this->faker->numberBetween(1, 20)
        ];
    }
}
