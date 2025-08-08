<?php

namespace App\Adapters\Shops;

use App\Adapters\Contracts\ShopAdapterInterface;
use App\Models\Store;
use App\Models\StoreToken;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

/**
 * Adapter for Magento platform.
 *
 * Communicates with Magento's APIs to fetch store and product data and handle
 * authentication token refreshes.
 */
class MagentoAdapter implements ShopAdapterInterface
{
    public function __construct(protected Store $store)
    {
    }

    /**
     * Base REST URL for the Magento instance.
     */
    protected function restBaseUrl(): string
    {
        return sprintf('https://%s/rest/V1', $this->store->domain);
    }

    /**
     * GraphQL endpoint for the Magento instance.
     */
    protected function graphqlUrl(): string
    {
        return sprintf('https://%s/graphql', $this->store->domain);
    }

    /**
     * Retrieve the latest access token for the store.
     */
    protected function token(): ?StoreToken
    {
        return $this->store->tokens()->latest()->first();
    }

    /**
     * Perform a REST API request against Magento.
     */
    protected function restRequest(string $method, string $endpoint, array $params = []): array
    {
        $token = $this->token()?->access_token;

        $response = Http::retry(3, 1000)
            ->withToken($token)
            ->$method($this->restBaseUrl() . $endpoint, $params);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json();
    }

    /**
     * Perform a GraphQL request against Magento.
     */
    protected function graphqlRequest(string $query, array $variables = []): array
    {
        $token = $this->token()?->access_token;

        $response = Http::retry(3, 1000)
            ->withToken($token)
            ->post($this->graphqlUrl(), [
                'query' => $query,
                'variables' => $variables,
            ]);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json();
    }

    public function getStoreInfo(): array
    {
        $query = <<<'GQL'
            query {
                storeConfig {
                    store_name
                    timezone
                }
            }
        GQL;

        $data = $this->graphqlRequest($query);

        $config = $data['data']['storeConfig'] ?? [];

        return [
            'name' => $config['store_name'] ?? null,
            'domain' => $this->store->domain,
            'timezone' => $config['timezone'] ?? null,
        ];
    }

    public function getProducts(int $limit = 100, int $page = 1): array
    {
        $query = <<<'GQL'
            query Products($pageSize: Int!, $currentPage: Int!) {
                products(pageSize: $pageSize, currentPage: $currentPage) {
                    items {
                        id
                        sku
                        name
                        price_range {
                            minimum_price {
                                regular_price {
                                    value
                                }
                            }
                        }
                    }
                }
            }
        GQL;

        $data = $this->graphqlRequest($query, [
            'pageSize' => $limit,
            'currentPage' => $page,
        ]);

        $items = $data['data']['products']['items'] ?? [];

        return array_map(fn (array $product) => $this->normalizeProduct($product), $items);
    }

    public function getProductById(string $externalId): ?array
    {
        $query = <<<'GQL'
            query Product($sku: String!) {
                products(filter: { sku: { eq: $sku } }) {
                    items {
                        id
                        sku
                        name
                        price_range {
                            minimum_price {
                                regular_price {
                                    value
                                }
                            }
                        }
                    }
                }
            }
        GQL;

        $data = $this->graphqlRequest($query, ['sku' => $externalId]);

        $product = Arr::first($data['data']['products']['items'] ?? []);

        return $product ? $this->normalizeProduct($product) : null;
    }

    /**
     * Normalize Magento product data to Feednity's internal structure.
     */
    protected function normalizeProduct(array $product): array
    {
        return [
            'id' => $product['id'] ?? null,
            'title' => $product['name'] ?? null,
            'sku' => $product['sku'] ?? null,
            'price' => $product['price_range']['minimum_price']['regular_price']['value'] ?? null,
        ];
    }

    public function refreshAccessToken(): void
    {
        $token = $this->token();

        if (! $token || ! $token->refresh_token) {
            return;
        }

        $oauthUrl = config('services.magento.oauth_url')
            ?? sprintf('https://%s/oauth/token', $this->store->domain);

        $response = Http::asForm()->post($oauthUrl, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
            'client_id' => config('services.magento.client_id'),
            'client_secret' => config('services.magento.client_secret'),
        ]);

        if ($response->failed()) {
            $response->throw();
        }

        $data = $response->json();

        $token->update([
            'access_token' => $data['access_token'] ?? $token->access_token,
            'expires_at' => isset($data['expires_in'])
                ? now()->addSeconds($data['expires_in'])
                : $token->expires_at,
        ]);
    }
}

