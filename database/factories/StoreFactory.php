<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'platform' => $this->faker->word(),
            'name' => $this->faker->company(),
            'domain' => $this->faker->domainName(),
            'metadata' => [],
        ];
    }
}
