<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feed>
 */
class FeedFactory extends Factory
{
    protected $model = Feed::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => $this->faker->word(),
            'channel' => $this->faker->word(),
            'format' => $this->faker->randomElement(['xml', 'csv', 'json']),
            'status' => 'active',
        ];
    }
}
