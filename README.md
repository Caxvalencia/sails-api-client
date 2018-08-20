# Sails Api Client
Library to connect and integrate Sails platform.

> Note that exists some dependencies are not abstracts to context,
 like are caching and encrypt functionalities.

## Installing
Run composer command:

```php
composer require caxvalencia/sails-api-client
```

## Getting started
Create a new instance

```php
// First configure security key parameter
$key = base64_encode(openssl_random_pseudo_bytes(32));

Config::set('security.key', base64_decode($key);

$apiUrl = \Sails\Api\Client\Sails::BASE_URI_FOR_PRODUCTION;
// or ...
// $apiUrl = 'https://api.sails.com.co';

$sailsApi = new \Sails\Api\Client\Sails(
    env(API_CLIENT_ID), // Variable environment
    env(API_CLIENT_SECRET),
    $apiUrl
);

// Methods
$sailsApi->saveProduct(Product $product);

$sailsApi->deleteProduct($productId);

$sailsApi->deleteStockUnit($stockUnitId);

$sailsApi->getOrders();

$sailsApi->updateProductStockUnits(Product $product);

```

## Services

#### ProductService
```php
use Sails\Api\Client\Entities\Product;
use Sails\Api\Client\Services\ProductService;

$productService = ProductService::getInstance($tokenResponse, $apiBaseUri);

$productService->create(Product $product);
$productService->update(Product $product);
$productService->save(Product $product);
$productService->delete($productId);
```

#### StockUnitService
```php
use Sails\Api\Client\Entities\StockUnit;
use Sails\Api\Client\Services\StockUnitService;

$stockUnitService = StockUnitService::getInstance($tokenResponse, $apiBaseUri);

$stockUnitService->create(array $stockUnits, $productId);
$stockUnitService->update(StockUnit $stockUnit);
$stockUnitService->updateBySku(StockUnit $stockUnit);
$stockUnitService->save(StockUnit $stockUnit, $productId = null);
$stockUnitService->delete($stockUnitId);
```

#### OrderService
```php
use Sails\Api\Client\Services\OrderService;

$orderService = OrderService::getInstance($tokenResponse, $apiBaseUri);

$orderService->all();
```
