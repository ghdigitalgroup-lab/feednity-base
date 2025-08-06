<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;

/**
 * Dummy adapter for SAP Commerce Cloud.
 *
 * TODO: Implement real SAP integration.
 */
class SAPAdapter implements ShopAdapterInterface
{
    public function __construct(protected Store $store)
    {
    }

    public function getStoreInfo(): array
    {
        // TODO
        return [];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        // TODO
        return [];
    }

    public function getProductById(string $externalId): ?array
    {
        // TODO
        return null;
    }

    public function refreshAccessToken(): void
    {
        // TODO
    }
}
