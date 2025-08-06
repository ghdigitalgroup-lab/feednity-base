<?php

namespace Tests\Unit\Adapters;

use App\Adapters\ShopAdapterResolver;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShopifyAdapterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_store_info(): void
    {
        $store = Store::factory()->create([
            'platform' => 'shopify',
            'domain' => 'example.myshopify.com',
        ]);
        StoreToken::factory()->for($store)->create();

        Http::fake([
            'https://example.myshopify.com/admin/api/2023-10/shop.json' => Http::response([
                'shop' => [
                    'name' => 'Demo Shop',
                    'domain' => 'example.myshopify.com',
                    'timezone' => 'UTC',
                ],
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $info = $adapter->getStoreInfo();

        $this->assertSame('Demo Shop', $info['name']);
        $this->assertSame('example.myshopify.com', $info['domain']);
        $this->assertSame('UTC', $info['timezone']);
    }

    public function test_get_products(): void
    {
        $store = Store::factory()->create([
            'platform' => 'shopify',
            'domain' => 'example.myshopify.com',
        ]);
        StoreToken::factory()->for($store)->create();

        Http::fake([
            'https://example.myshopify.com/admin/api/2023-10/products.json*' => Http::response([
                'products' => [
                    [
                        'id' => 1,
                        'title' => 'Test Product',
                        'variants' => [
                            ['sku' => 'SKU1', 'price' => '10.00'],
                        ],
                    ],
                ],
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $products = $adapter->getProducts(50, 1);

        $this->assertCount(1, $products);
        $this->assertSame('Test Product', $products[0]['title']);
        $this->assertSame('SKU1', $products[0]['sku']);
    }

    public function test_get_product_by_id(): void
    {
        $store = Store::factory()->create([
            'platform' => 'shopify',
            'domain' => 'example.myshopify.com',
        ]);
        StoreToken::factory()->for($store)->create();

        Http::fake([
            'https://example.myshopify.com/admin/api/2023-10/products/123.json' => Http::response([
                'product' => [
                    'id' => 123,
                    'title' => 'Single Product',
                    'variants' => [
                        ['sku' => 'SKU123', 'price' => '20.00'],
                    ],
                ],
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $product = $adapter->getProductById('123');

        $this->assertSame('Single Product', $product['title']);
        $this->assertSame('SKU123', $product['sku']);
    }

    public function test_refresh_access_token_updates_token(): void
    {
        config([
            'services.shopify.client_id' => 'id',
            'services.shopify.client_secret' => 'secret',
        ]);

        $store = Store::factory()->create([
            'platform' => 'shopify',
            'domain' => 'example.myshopify.com',
        ]);
        $token = StoreToken::factory()->for($store)->create([
            'access_token' => 'old',
            'refresh_token' => 'refresh',
            'expires_at' => now(),
        ]);

        Http::fake([
            'https://example.myshopify.com/admin/oauth/access_token' => Http::response([
                'access_token' => 'newtoken',
                'expires_in' => 3600,
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $adapter->refreshAccessToken();

        $token->refresh();
        $this->assertSame('newtoken', $token->access_token);
        $this->assertTrue($token->expires_at->greaterThan(now()));
    }
}
