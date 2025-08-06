<?php

namespace Database\Factories;

use App\Models\ProductCache;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductCache>
 */
class ProductCacheFactory extends Factory
{
    protected $model = ProductCache::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'external_id' => (string) $this->faker->unique()->numberBetween(1, 100000),
            'data' => ['title' => $this->faker->sentence()],
            'fetched_at' => now(),
        ];
    }
}
