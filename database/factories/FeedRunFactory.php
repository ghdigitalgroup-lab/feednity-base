<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedRun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeedRun>
 */
class FeedRunFactory extends Factory
{
    protected $model = FeedRun::class;

    public function definition(): array
    {
        $start = now();
        return [
            'feed_id' => Feed::factory(),
            'started_at' => $start,
            'completed_at' => $start->copy()->addMinutes(5),
            'success' => true,
            'log' => null,
        ];
    }
}
