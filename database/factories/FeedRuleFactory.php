<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeedRule>
 */
class FeedRuleFactory extends Factory
{
    protected $model = FeedRule::class;

    public function definition(): array
    {
        return [
            'feed_id' => Feed::factory(),
            'type' => 'filter',
            'config' => [],
            'sort_order' => 1,
        ];
    }
}
