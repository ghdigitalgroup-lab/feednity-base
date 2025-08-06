<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeedFile>
 */
class FeedFileFactory extends Factory
{
    protected $model = FeedFile::class;

    public function definition(): array
    {
        return [
            'feed_id' => Feed::factory(),
            'path' => $this->faker->filePath(),
            'format' => $this->faker->randomElement(['xml', 'csv', 'json']),
            'generated_at' => now(),
        ];
    }
}
