<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;

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

    public function getStoreInfo(): array
    {
        // TODO: Implement WooCommerce store info retrieval
        return [];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        // TODO: Implement WooCommerce product listing
        return [];
    }

    public function getProductById(string $externalId): ?array
    {
        // TODO: Implement WooCommerce single product retrieval
        return null;
    }

    public function refreshAccessToken(): void
    {
        // WooCommerce typically uses basic auth or application tokens
    }
}
