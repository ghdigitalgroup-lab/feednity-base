<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;

/**
 * Adapter for Shopware platform.
 *
 * TODO: Implement Admin API integration.
 */
class ShopwareAdapter implements ShopAdapterInterface
{
    public function __construct(protected Store $store)
    {
    }

    public function getStoreInfo(): array
    {
        // TODO: Implement Shopware store info retrieval
        return [];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        // TODO: Implement Shopware product listing
        return [];
    }

    public function getProductById(string $externalId): ?array
    {
        // TODO: Implement Shopware single product retrieval
        return null;
    }

    public function refreshAccessToken(): void
    {
        // TODO: Implement Shopware token refresh
    }
}
