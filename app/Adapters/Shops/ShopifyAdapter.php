<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

/**
 * Adapter for Shopify platform using the Admin API.
 */
class ShopifyAdapter implements ShopAdapterInterface
{
    protected Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Build the base API URL for the store.
     */
    protected function baseUrl(): string
    {
        return sprintf('https://%s/admin/api/2023-10', $this->store->domain);
    }

    /**
     * Retrieve the most recent token associated with the store.
     */
    protected function token(): ?StoreToken
    {
        return $this->store->tokens()->latest()->first();
    }

    /**
     * Perform an HTTP request against the Shopify API handling retries and errors.
     */
    protected function request(string $method, string $endpoint, array $params = []): array
    {
        $token = $this->token()?->access_token;

        $response = Http::retry(3, 1000)
            ->withToken($token)
            ->$method($this->baseUrl() . $endpoint, $params);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json();
    }

    public function getStoreInfo(): array
    {
        $data = $this->request('get', '/shop.json');

        $shop = $data['shop'] ?? [];

        return [
            'name' => $shop['name'] ?? null,
            'domain' => $shop['domain'] ?? null,
            'timezone' => $shop['timezone'] ?? null,
        ];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        $data = $this->request('get', '/products.json', [
            'limit' => $limit,
            'page' => $page,
        ]);

        return array_map(fn (array $product) => $this->normalizeProduct($product), $data['products'] ?? []);
    }

    public function getProductById(string $externalId): ?array
    {
        $data = $this->request('get', sprintf('/products/%s.json', $externalId));

        return isset($data['product']) ? $this->normalizeProduct($data['product']) : null;
    }

    /**
     * Normalize Shopify product structure to Feednity's internal representation.
     */
    protected function normalizeProduct(array $product): array
    {
        $variant = Arr::first($product['variants'] ?? []) ?? [];

        return [
            'id' => $product['id'] ?? null,
            'title' => $product['title'] ?? null,
            'sku' => $variant['sku'] ?? null,
            'price' => $variant['price'] ?? null,
        ];
    }

    public function refreshAccessToken(): void
    {
        $token = $this->token();

        if (! $token || ! $token->refresh_token) {
            return;
        }

        $response = Http::asForm()->post(sprintf('https://%s/admin/oauth/access_token', $this->store->domain), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
            'client_id' => config('services.shopify.client_id'),
            'client_secret' => config('services.shopify.client_secret'),
        ]);

        if ($response->failed()) {
            $response->throw();
        }

        $data = $response->json();

        $token->update([
            'access_token' => $data['access_token'] ?? $token->access_token,
            'expires_at' => isset($data['expires_in']) ? now()->addSeconds($data['expires_in']) : $token->expires_at,
        ]);
    }
}
