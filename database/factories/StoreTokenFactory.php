<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StoreToken>
 */
class StoreTokenFactory extends Factory
{
    protected $model = StoreToken::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'access_token' => Str::random(32),
            'refresh_token' => Str::random(32),
            'scopes' => [],
            'expires_at' => now()->addMonth(),
        ];
    }
}
