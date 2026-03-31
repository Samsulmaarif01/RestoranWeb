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
        $menuItems = [
            [
                'name' => 'Pizza Margherita',
                'description' => 'Pizza klasik dengan saus tomat segar, keju mozzarella, dan daun basil.',
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Grilled Beef Steak',
                'description' => 'Steak daging sapi premium yang dipanggang dengan bumbu rempah rahasia.',
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Healthy Garden Salad',
                'description' => 'Campuran sayuran segar dengan dressing olive oil dan lemon.',
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Burger Cheese Deluxe',
                'description' => 'Burger daging sapi juicy dengan keju cheddar yang meleleh dan sayuran.',
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Fresh Orange Juice',
                'description' => 'Jus jeruk murni yang diperas segar setiap hari tanpa pemanis buatan.',
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Iced Coffee Latte',
                'description' => 'Perpaduan sempurna antara espresso premium dan susu segar yang dingin.',
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Strawberry Smoothie',
                'description' => 'Minuman sehat dari buah strawberry segar yang dihaluskan dengan yogurt.',
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1502741224143-90386d7f8c82?auto=format&fit=crop&q=80&w=600&h=600',
            ],
            [
                'name' => 'Tropical Fruit Punch',
                'description' => 'Kombinasi berbagai buah tropis segar dalam satu gelas minuman dingin.',
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1544145945-f904253db0ad?auto=format&fit=crop&q=80&w=600&h=600',
            ],
        ];

        $item = $this->faker->randomElement($menuItems);

        return [
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => $this->faker->numberBetween(15000, 150000),
            'category_id' => $item['category_id'],
            'image' => $item['image'],
            'is_active' => true,
        ];
    }
}