<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Requests;

use Friendz\FdzBroker\Api\Util\Arrayable;
use Friendz\Orderz\Api\Models\User;

class CreateOrder implements Arrayable
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
     * @return array
     */
    function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
            'productId' => $this->productId,
            'user' => $this->user->toArray()
        ];
    }
}