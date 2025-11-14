<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(10000, 50000),
            'stock' => $this->faker->numberBetween(0, 100),
            'desc' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'food'),
            'category_id' => Category::factory(),
        ];
    }
}
