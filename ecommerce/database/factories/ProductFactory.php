<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => 'P-' . date('md') . '-' . $this->faker->unique()->numerify('######'),
            'category_id' => $this->faker->numberBetween(1, 5),
            'code' => $this->faker->unique()->bothify('??#?##'),
            'name' => $this->faker->word(2),
            'varian' => $this->faker->word(1),
            'description' => $this->faker->sentence(2),
            'image' => $this->faker->randomNumber(1, 10) . '.jpg',
            'price' => $this->faker->randomNumber(4),
            'created_at' => Carbon::now()->format('Y-m-d')
        ];
    }
}
