<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;

/**
 * Adapter for Magento platform.
 *
 * TODO: Implement API calls and normalization logic.
 */
class MagentoAdapter implements ShopAdapterInterface
{
    public function __construct(protected Store $store)
    {
    }

    public function getStoreInfo(): array
    {
        // TODO: Implement Magento store info retrieval
        return [];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        // TODO: Implement Magento product listing
        return [];
    }

    public function getProductById(string $externalId): ?array
    {
        // TODO: Implement Magento single product retrieval
        return null;
    }

    public function refreshAccessToken(): void
    {
        // TODO: Implement Magento token refresh
    }
}
