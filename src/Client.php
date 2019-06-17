<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api;

use Friendz\Orderz\Api\Exceptions\ApiException;
use Friendz\Orderz\Api\Exceptions\MalformedResponseException;
use Friendz\Orderz\Api\Models\Order;
use Friendz\Orderz\Api\Models\Product;
use Friendz\Orderz\Api\Requests\CreateOrder;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class Client
{
    /**
     * @var string
     */
    private $baseUrl;

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
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
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

        $response = $this->sendPostRequest('/', $data);
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
     * @param string $baseUrl
     * @return Client
     */
    public function setBaseUrl(string $baseUrl): Client
    {
        $this->baseUrl = $baseUrl;
        return $this;
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
        return $this->baseUrl;
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
        $url = $this->baseUrl . $api;
        $request = new Request('POST', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ], json_encode($data));
        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $e) {
            $response = $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage());
        }

        return json_decode((string)$response->getBody());
    }

    /**
     * @param string $api
     * @return mixed|null
     * @throws ApiException
     * @throws MalformedResponseException
     */
    private function sendGetRequest(string $api)
    {
        $url = $this->baseUrl . $api;
        $request = new Request('GET', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $e) {
            $response = $this->handleClientException($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage());
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
            $responseDecoded = json_encode((string)$response->getBody());
            if (!property_exists($responseDecoded, 'status') || !property_exists($responseDecoded, 'message')) {
                throw new MalformedResponseException(
                    sprintf('`status` or `message` attributes not defined in response body. Raw response body: %s', json_encode($responseDecoded))
                );
            }
            if ($responseDecoded->status !== 'error') {
                throw new MalformedResponseException(
                    sprintf('Response was unsuccessful but status is not `error`. Raw response body: %s', json_encode($responseDecoded))
                );
            }

            // TODO: more specific error handling!

            throw new ApiException($responseDecoded->message);
        }
        throw new ApiException($e->getMessage());
    }

    /**
     * @param $data
     * @return array
     * @throws MalformedResponseException
     */
    private function responseToProductList($data): array
    {
        if (!$data) {
            return null;
        }

        if (!is_array($data)) {
            $data = (object)$data;
        }

        if (!property_exists($data, 'status') || !property_exists($data, 'products')) {
            throw new MalformedResponseException(
                sprintf('`status` or `products` attributes not defined in response body. Raw response body: %s', json_encode($data))
            );
        }

        if ($data->status !== 'success') {
            throw new MalformedResponseException(
                sprintf('Response was successful but status is not `success`. Raw response body: %s', json_encode($data))
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
                $product->available,
                $product->total_quantity,
                $product->allow_less_than_zero_quantity
            );
        }

        return $result;
    }
}