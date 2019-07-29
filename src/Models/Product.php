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
     * @param int $id
     * @param string $name
     * @param float $cost
     * @param bool $available
     * @param float $discountPercentage
     * @return Product
     */
    public static function make(
        int $id,
        string $name,
        float $cost,
        bool $available,
        float $discountPercentage
    ): self
    {
        $model = new static;

        $model->id = $id;
        $model->name = $name;
        $model->cost = $cost;
        $model->available = $available;
        $model->discountPercentage = $discountPercentage;

        return $model;
    }
}