<?php

namespace App\Adapters\Contracts;

/**
 * Unified contract for interacting with external shop platforms.
 */
interface ShopAdapterInterface
{
    /**
     * Retrieve basic store metadata such as name, domain and timezone.
     */
    public function getStoreInfo(): array;

    /**
     * Retrieve a paginated list of products.
     *
     * @param int $limit Number of products per page.
     * @param int $page  Page number starting from 1.
     *
     * @return array<int, array>
     */
    public function getProducts(int $limit = 100, int $page = 1): array;

    /**
     * Retrieve a single product by its external identifier.
     */
    public function getProductById(string $externalId): ?array;

    /**
     * Refresh the access token used for authenticating API calls.
     */
    public function refreshAccessToken(): void;
}
