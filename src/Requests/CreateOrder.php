<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Requests;

use Friendz\Orderz\Api\Models\User;

class CreateOrder
{
    /**
     * @var string
     */
    public $externalId;

    /**
     * @var string
     */
    public $productId;

    /**
     * @var User
     */
    public $user;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @return array
     */
    function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
            'productId' => $this->productId,
            'user' => $this->user->toArray(),
            'quantity' => $this->quantity
        ];
    }
}