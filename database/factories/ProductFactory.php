<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Category;
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
    public function definition(): array
    {
        return [
            'category_id'   => Category::factory(),
            'branch_id'     => Branch::factory(),
            'name'          => fake()->words(2, true),
            'sku'           => strtoupper(fake()->unique()->bothify('SKU-#####')), // ex: SKU-82913
            'unit'          => fake()->randomElement(['pcs', 'kg', 'box', 'liter']),
            'image'         => fake()->imageUrl(640, 480, 'products', true),
            'description'   => fake()->sentence(10),
            'price'         => fake()->randomFloat(2, 1000, 100000), // 1k - 100k
            'stock'         => fake()->numberBetween(0, 100),
            'barcode'       => fake()->ean13(), // 13 digit barcode
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
