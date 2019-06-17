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
     * @var bool
     */
    public $available;

    /**
     * @var int
     */
    public $totalQuantity;

    /**
     * @var bool
     */
    public $allowLessThanZeroQuantity;

    /**
     * @param int $id
     * @param string $name
     * @param bool $available
     * @param int $totalQuantity
     * @param bool $allowLessThanZeroQuantity
     * @return Product
     */
    public static function make(
        int $id,
        string $name,
        bool $available,
        int $totalQuantity,
        bool $allowLessThanZeroQuantity
    ): self
    {
        $model = new static;

        $model->id = $id;
        $model->name = $name;
        $model->available = $available;
        $model->totalQuantity = $totalQuantity;
        $model->allowLessThanZeroQuantity = $allowLessThanZeroQuantity;

        return $model;
    }
}