<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(1000, 100000),
            'category_id' => $this->faker->numberBetween(1, 2),
            'image' => $this->faker->imageUrl,
            'is_active' => $this->faker->boolean,
        ];
    }
}
