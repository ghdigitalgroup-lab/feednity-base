<?php

namespace App\Adapters;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Adapters\Shops\{MagentoAdapter, PrestaShopAdapter, SAPAdapter, SalesforceAdapter, ShopifyAdapter, ShopwareAdapter, WooCommerceAdapter};
use App\Models\Store;

/**
 * Factory class responsible for resolving the correct adapter for a store.
 */
class ShopAdapterResolver
{
    public static function resolve(Store $store): ShopAdapterInterface
    {
        return match ($store->platform) {
            'shopify' => new ShopifyAdapter($store),
            'magento' => new MagentoAdapter($store),
            'woocommerce' => new WooCommerceAdapter($store),
            'shopware' => new ShopwareAdapter($store),
            'prestashop' => new PrestaShopAdapter($store),
            'salesforce' => new SalesforceAdapter($store),
            'sap' => new SAPAdapter($store),
            default => throw new \RuntimeException("Unsupported platform: {$store->platform}"),
        };
    }
}
