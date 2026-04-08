<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        $slugBase = Str::slug($name);

        $thumbnail = fake()->imageUrl(400, 400, 'products', true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => $slugBase.'-'.fake()->unique()->numerify('####'),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 5, 500),
            'currency' => 'EUR',
            'thumbnail' => $thumbnail,
            'images' => [
                $thumbnail,
                fake()->imageUrl(800, 800, 'products', true),
            ],
            'type' => fake()->randomElement(['physical', 'digital', 'bundle']),
            'format' => fake()->randomElement(['standard', 'compact', 'large']),
            'in_stock' => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'in_stock' => false,
        ]);
    }
}
