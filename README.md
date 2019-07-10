# orderz-php-sdk

## Installation
`
  composer require friendz/orderz-php-sdk
`

## Requirements

- **PHP** >= `7.2`
- **guzzlehttp/guzzle** = `6.3.3`
- **nesbot/carbon** = `2.19.2`

## Basic Usage

```php
use Friendz\Orderz\Api\Client as OrderzClient;
use Friendz\Orderz\Api\Models\User as UserModel;
use Friendz\Orderz\Api\Requests\CreateOrder as CreateOrderRequest;

function foo(): array
{
  $client = new OrderzClient('api-url', 'api-token');
  
  return $client->getProducts();
}

function bar(int $productId)
{
  $client = new OrderzClient('api-url', 'api-token');
  
  $orderRequest = new CreateOrderRequest;
  $orderRequest->externalId = 'your-unique-id';
  $orderRequest->productId = $productId;
  $orderRequest->quantity = 1;
  $orderRequest->user = new UserModel(
    'John',
    'Doe',
    'john.doe@email.com',
    'Sesame Street, 1119'
  );
  
  $client->createOrder($orderRequest);
}
```
