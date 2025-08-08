<?php

namespace Tests\Feature;

use App\Adapters\Shops\MagentoAdapter;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MagentoAdapterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Limit migrations to only those needed for store and token models.
     */
    protected function migrateFreshUsing()
    {
        return [
            '--path' => [
                'database/migrations/2025_08_06_142746_create_teams_table.php',
                'database/migrations/2025_08_06_142749_create_stores_table.php',
                'database/migrations/2025_08_06_142753_create_store_tokens_table.php',
            ],
        ];
    }

    protected function beforeRefreshingDatabase()
    {
        putenv('DB_DATABASE=:memory:');
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    public function test_get_store_info_returns_normalized_data(): void
    {
        Http::fake([
            'https://magento.test/graphql' => Http::response([
                'data' => [
                    'storeConfig' => [
                        'store_name' => 'Demo Store',
                        'timezone' => 'UTC',
                    ],
                ],
            ]),
        ]);

        $store = Store::factory()->create([
            'platform' => 'magento',
            'domain' => 'magento.test',
        ]);
        StoreToken::factory()->create(['store_id' => $store->id]);

        $info = (new MagentoAdapter($store))->getStoreInfo();

        $this->assertSame('Demo Store', $info['name']);
        $this->assertSame('magento.test', $info['domain']);
        $this->assertSame('UTC', $info['timezone']);
    }

    public function test_get_products_returns_normalized_list(): void
    {
        Http::fake([
            'https://magento.test/graphql' => Http::response([
                'data' => [
                    'products' => [
                        'items' => [
                            [
                                'id' => 1,
                                'sku' => 'sku-1',
                                'name' => 'First',
                                'price_range' => [
                                    'minimum_price' => [
                                        'regular_price' => ['value' => 10],
                                    ],
                                ],
                            ],
                            [
                                'id' => 2,
                                'sku' => 'sku-2',
                                'name' => 'Second',
                                'price_range' => [
                                    'minimum_price' => [
                                        'regular_price' => ['value' => 20],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $store = Store::factory()->create([
            'platform' => 'magento',
            'domain' => 'magento.test',
        ]);
        StoreToken::factory()->create(['store_id' => $store->id]);

        $products = (new MagentoAdapter($store))->getProducts(2, 1);

        $this->assertCount(2, $products);
        $this->assertSame([
            'id' => 1,
            'title' => 'First',
            'sku' => 'sku-1',
            'price' => 10,
        ], $products[0]);
    }

    public function test_get_product_by_id_returns_single_product(): void
    {
        Http::fake([
            'https://magento.test/graphql' => Http::response([
                'data' => [
                    'products' => [
                        'items' => [
                            [
                                'id' => 5,
                                'sku' => 'prod-5',
                                'name' => 'Fifth',
                                'price_range' => [
                                    'minimum_price' => [
                                        'regular_price' => ['value' => 15],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $store = Store::factory()->create([
            'platform' => 'magento',
            'domain' => 'magento.test',
        ]);
        StoreToken::factory()->create(['store_id' => $store->id]);

        $product = (new MagentoAdapter($store))->getProductById('prod-5');

        $this->assertSame([
            'id' => 5,
            'title' => 'Fifth',
            'sku' => 'prod-5',
            'price' => 15,
        ], $product);
    }

    public function test_refresh_access_token_updates_token(): void
    {
        config([
            'services.magento.client_id' => 'client',
            'services.magento.client_secret' => 'secret',
            'services.magento.oauth_url' => 'https://magento.test/oauth/token',
        ]);

        Http::fake([
            'https://magento.test/oauth/token' => Http::response([
                'access_token' => 'new-token',
                'expires_in' => 3600,
            ]),
        ]);

        $store = Store::factory()->create([
            'platform' => 'magento',
            'domain' => 'magento.test',
        ]);

        $token = StoreToken::factory()->create([
            'store_id' => $store->id,
            'access_token' => 'old-token',
            'refresh_token' => 'refresh-token',
            'expires_at' => now()->subDay(),
        ]);

        (new MagentoAdapter($store))->refreshAccessToken();

        $token->refresh();

        $this->assertSame('new-token', $token->access_token);
        $this->assertTrue($token->expires_at->gt(now()));
    }
}

