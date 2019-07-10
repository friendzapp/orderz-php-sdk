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
     * @param int $id
     * @param string $name
     * @param bool $available
     * @return Product
     */
    public static function make(
        int $id,
        string $name,
        bool $available
    ): self
    {
        $model = new static;

        $model->id = $id;
        $model->name = $name;
        $model->available = $available;

        return $model;
    }
}