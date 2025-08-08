<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Support\Facades\Http;

/**
 * Adapter for WooCommerce platform.
 *
 * TODO: Implement REST API calls with authentication.
 */
class WooCommerceAdapter implements ShopAdapterInterface
{
    public function __construct(protected Store $store)
    {
    }

    /**
     * Build the base URL for WooCommerce API.
     */
    protected function baseUrl(): string
    {
        return sprintf('https://%s/wp-json/wc/v3', $this->store->domain);
    }

    /**
     * Retrieve latest credentials for the store.
     */
    protected function token(): ?StoreToken
    {
        if ($this->store->relationLoaded('tokens')) {
            return $this->store->getRelation('tokens')->sortByDesc('id')->first();
        }

        return $this->store->tokens()->latest()->first();
    }

    /**
     * Perform an authenticated request against the WooCommerce API.
     */
    protected function request(string $method, string $endpoint, array $params = []): array
    {
        $token = $this->token();
        $key = $token?->access_token;
        $secret = $token?->refresh_token;

        $response = Http::retry(3, 1000)
            ->withBasicAuth($key, $secret)
            ->$method($this->baseUrl() . $endpoint, $params);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json();
    }

    public function getStoreInfo(): array
    {
        $data = $this->request('get', '');

        return [
            'name' => $data['name'] ?? null,
            'domain' => $data['url'] ?? $this->store->domain,
            'timezone' => $data['timezone'] ?? null,
        ];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        $data = $this->request('get', '/products', [
            'per_page' => $limit,
            'page' => $page,
        ]);

        return array_map(fn (array $product) => $this->normalizeProduct($product), $data);
    }

    public function getProductById(string $externalId): ?array
    {
        $data = $this->request('get', sprintf('/products/%s', $externalId));

        return $data ? $this->normalizeProduct($data) : null;
    }

    /**
     * Normalize WooCommerce product structure to Feednity's format.
     */
    protected function normalizeProduct(array $product): array
    {
        return [
            'id' => $product['id'] ?? null,
            'title' => $product['name'] ?? null,
            'sku' => $product['sku'] ?? null,
            'price' => $product['price'] ?? null,
        ];
    }

    public function refreshAccessToken(): void
    {
        // WooCommerce typically uses basic auth or application tokens
    }
}
