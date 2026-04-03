<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => 'product-' . $this->faker->numerify('###') . '.jpg',
        ];
    }
}