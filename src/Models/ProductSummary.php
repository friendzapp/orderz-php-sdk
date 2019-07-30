<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

class ProductSummary
{
    /**
     * @var int
     */
    public $productId;

    /**
     * @var int
     */
    public $orderCount;

    /**
     * @var float
     */
    public $total;

    /**
     * @var float
     */
    public $cps;

    /**
     * @param int $productId
     * @param int $orderCount
     * @param float $total
     * @param float $cps
     * @return ProductSummary
     */
    public static function make(
        int $productId,
        int $orderCount,
        float $total,
        float $cps
    ): self
    {
        $model = new static;

        $model->productId = $productId;
        $model->orderCount = $orderCount;
        $model->total = $total;
        $model->cps = $cps;

        return $model;
    }
}