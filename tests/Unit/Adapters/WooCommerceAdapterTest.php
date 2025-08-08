<?php

namespace Tests\Unit\Adapters;

use App\Adapters\ShopAdapterResolver;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WooCommerceAdapterTest extends TestCase
{
    public function test_get_products(): void
    {
        $store = new Store(['platform' => 'woocommerce', 'domain' => 'example.com']);
        $token = new StoreToken(['access_token' => 'ck_123', 'refresh_token' => 'cs_456']);
        $store->setRelation('tokens', collect([$token]));

        Http::fake([
            'https://example.com/wp-json/wc/v3/products*' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Test Product',
                    'sku' => 'SKU1',
                    'price' => '10.00',
                ],
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $products = $adapter->getProducts(50, 2);

        $this->assertCount(1, $products);
        $this->assertSame('Test Product', $products[0]['title']);
        $this->assertSame('SKU1', $products[0]['sku']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/wp-json/wc/v3/products?per_page=50&page=2'
                && $request->hasHeader('Authorization', 'Basic ' . base64_encode('ck_123:cs_456'));
        });
    }

    public function test_get_product_by_id(): void
    {
        $store = new Store(['platform' => 'woocommerce', 'domain' => 'example.com']);
        $token = new StoreToken(['access_token' => 'ck_123', 'refresh_token' => 'cs_456']);
        $store->setRelation('tokens', collect([$token]));

        Http::fake([
            'https://example.com/wp-json/wc/v3/products/123' => Http::response([
                'id' => 123,
                'name' => 'Single Product',
                'sku' => 'SKU123',
                'price' => '20.00',
            ]),
        ]);

        $adapter = ShopAdapterResolver::resolve($store);
        $product = $adapter->getProductById('123');

        $this->assertSame('Single Product', $product['title']);
        $this->assertSame('SKU123', $product['sku']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/wp-json/wc/v3/products/123'
                && $request->hasHeader('Authorization', 'Basic ' . base64_encode('ck_123:cs_456'));
        });
    }
}

