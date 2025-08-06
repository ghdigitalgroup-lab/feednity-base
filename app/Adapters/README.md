# Shop Adapters

This directory contains the adapter layer used to communicate with third-party
shop platforms (e.g. Shopify, Magento, WooCommerce). Each adapter implements the
[`ShopAdapterInterface`](Contracts/ShopAdapterInterface.php) providing a unified
API for retrieving store information and products.

## Usage

```php
use App\Adapters\ShopAdapterResolver;
use App\Models\Store;

$store = Store::find($id);
$adapter = ShopAdapterResolver::resolve($store);

$products = $adapter->getProducts(50, 1);
$info = $adapter->getStoreInfo();
```

## Adding New Adapters

1. Create a class in `app/Adapters/Shops` implementing
   `ShopAdapterInterface`.
2. Register it within `ShopAdapterResolver::resolve`.
3. Provide tests mocking external API responses.

Adapters for PrestaShop, Salesforce, and SAP are currently placeholders and
should be completed in the future.
