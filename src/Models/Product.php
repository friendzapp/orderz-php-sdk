<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

class Product
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $cost;

    /**
     * @var bool
     */
    public $available;

    /**
     * @var float
     */
    public $discountPercentage;

    /**
     * @var string|null
     */
    public $remoteService;

    /**
     * @param int $id
     * @param string $name
     * @param float $cost
     * @param bool $available
     * @param float $discountPercentage
     * @param string|null $remoteService
     * @return Product
     */
    public static function make(
        int $id,
        string $name,
        float $cost,
        bool $available,
        float $discountPercentage,
        ?string $remoteService = null
    ): self
    {
        $model = new static;

        $model->id = $id;
        $model->name = $name;
        $model->cost = $cost;
        $model->available = $available;
        $model->discountPercentage = $discountPercentage;
        $model->remoteService = $remoteService;

        return $model;
    }
}