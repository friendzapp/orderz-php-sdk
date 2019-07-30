<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api;

use Friendz\Orderz\Api\Models\ProductSummary;
use Friendz\Orderz\Api\Requests\ProductsSummary as ProductsSummaryRequest;
use function GuzzleHttp\Psr7\modify_request;
use GuzzleHttp\Psr7\Request;
use Friendz\Orderz\Api\Models\Order;
use GuzzleHttp\Client as GuzzleClient;
use Friendz\Orderz\Api\Models\Product;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Friendz\Orderz\Api\Models\OrderResult;
use Friendz\Orderz\Api\Requests\CreateOrder;
use Friendz\Orderz\Api\Exceptions\ApiException;
use Friendz\Orderz\Api\Exceptions\MalformedResponseException;

class Client
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var GuzzleClient
     */
    private $httpClient;

    /**
     * Client constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;

        $this->httpClient = new GuzzleClient();
    }

    /**
     * @param CreateOrder $request
     * @return Order|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    public function createOrder(CreateOrder $request): ?Order
    {
        $data = $request->toArray();

        $response = $this->sendPostRequest('/orders', $data);

        return $this->responseToOrder($response);
    }

    /**
     * @return array
     * @throws ApiException
     * @throws MalformedResponseException
     */
    public function getProducts(): array
    {
        $response = $this->sendGetRequest('/products');

        return $this->responseToProductList($response);
    }

    /**
     * @param string|int $orderId
     * @return Order|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    public function getOrder($orderId): ?Order
    {
        $response = $this->sendGetRequest("/orders/{$orderId}");

        return $this->responseToOrder($response);
    }

    /**
     * @param ProductsSummaryRequest $summaryRequest
     * @return array
     * @throws ApiException
     * @throws MalformedResponseException
     */
    public function getProductsSummary(ProductsSummaryRequest $summaryRequest)
    {
        $response = $this->sendGetRequest('/summary/orders', $summaryRequest);

        return $this->responseToProductsSummary($response);
    }

    /**
     * @param string $token
     * @return Client
     */
    public function setToken(string $token): Client
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $api
     * @param array $data
     * @return mixed|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    private function sendPostRequest(string $api, array $data)
    {
        $url = $this->getBaseUrl() . $api;
        $request = new Request('POST', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ], json_encode($data));

        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $e) {
            $response = $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), true);
        }

        return json_decode((string)$response->getBody());
    }

    /**
     * @param string $api
     * @param null $data
     * @return mixed|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    private function sendGetRequest(string $api, $data = null)
    {
        $url = $this->getBaseUrl() . $api;
        $request = new Request('GET', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        if ($data) {
            $request = modify_request($request, [
                'body' => json_encode($data)
            ]);
        }

        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $e) {
            $response = $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), true);
        }

        return json_decode((string)$response->getBody());
    }

    /**
     * @param ClientException $e
     * @return \Psr\Http\Message\ResponseInterface|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    private function handleClientException(ClientException $e) {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 && $statusCode < 300) {
            return $response;
        }

        if ($statusCode >= 400 && $statusCode < 500) {
            // Client Error
            $responseDecoded = json_decode((string)$response->getBody());

            $shouldRetry = false;
            if (property_exists($responseDecoded, 'data') && property_exists($responseDecoded->data, 'shouldRetry')) {
                $shouldRetry = $responseDecoded->data->shouldRetry;
            }

            if (!property_exists($responseDecoded, 'status') || !property_exists($responseDecoded, 'message')) {
                throw new MalformedResponseException(
                    sprintf('`status` or `message` attributes not defined in response body. Raw response body: %s', json_encode($responseDecoded)),
                    $shouldRetry
                );
            }

            if ($responseDecoded->status !== 'error') {
                throw new MalformedResponseException(
                    sprintf('Response was unsuccessful but status is not `error`. Raw response body: %s', json_encode($responseDecoded)),
                    $shouldRetry
                );
            }

            throw new ApiException($responseDecoded->message, $shouldRetry);
        }

        throw new ApiException($e->getMessage(), true);
    }

    /**
     * @param $data
     * @return Order|null
     * @throws MalformedResponseException
     */
    private function responseToOrder($data): ?Order
    {
        if (!$data) {
            return null;
        }

        if (!is_object($data)) {
            $data = (object)$data;
        }

        if (!property_exists($data, 'status') || !property_exists($data, 'order')) {
            throw new MalformedResponseException(
                sprintf('`status` or `order` attributes not defined in response body. Raw response body: %s', json_encode($data)),
                true
            );
        }

        $order = $data->order;

        $results = [];
        foreach ($order->result as $result) {
            $results[] = OrderResult::make(
                $result->link,
                $result->code
            );
        }

        return Order::make(
            (int)$order->id,
            (string)$order->externalId,
            $order->status,
            $results
        );
    }

    /**
     * @param $data
     * @return array
     * @throws MalformedResponseException
     */
    private function responseToProductList($data): array
    {
        if (!$data) {
            return [];
        }

        if (!is_object($data)) {
            $data = (object)$data;
        }

        if (!property_exists($data, 'status') || !property_exists($data, 'products')) {
            throw new MalformedResponseException(
                sprintf('`status` or `products` attributes not defined in response body. Raw response body: %s', json_encode($data)),
                true
            );
        }

        $products = $data->products;
        if (!$products || !is_array($products)) {
            return [];
        }

        $result = [];
        foreach ($products as $product) {
            $result[] = Product::make(
                $product->id,
                $product->name,
                $product->cost,
                $product->available,
                $product->discount_percentage
            );
        }

        return $result;
    }

    /**
     * @param $data
     * @return array
     * @throws MalformedResponseException
     */
    private function responseToProductsSummary($data): array
    {
        if (!$data) {
            return [];
        }

        if (!is_object($data)) {
            $data = (object)$data;
        }

        if (!property_exists($data, 'status') || !property_exists($data, 'entries')) {
            throw new MalformedResponseException(
                sprintf('`status` or `entries` attributes not defined in response body. Raw response body: %s', json_encode($data)),
                true
            );
        }

        $entries = $data->entries;
        if (!$entries || !is_array($entries)) {
            return [];
        }

        $result = [];
        foreach ($entries as $entry) {
            $result[] = ProductSummary::make(
                $entry->product_id,
                $entry->order_count,
                $entry->total,
                $entry->cps
            );
        }

        return $result;
    }

    private const BASE_URL = 'https://orderz.amazing-friendz.com/api';
}