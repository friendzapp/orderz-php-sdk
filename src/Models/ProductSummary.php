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
    public $totalCost;

    /**
     * @var float
     */
    public $unitCost;

    /**
     * @var float
     */
    public $cps;

    /**
     * @param int $productId
     * @param int $orderCount
     * @param float $totalCost
     * @param float $unitCost
     * @param float $cps
     * @return ProductSummary
     */
    public static function make(
        int $productId,
        int $orderCount,
        float $totalCost,
        float $unitCost,
        float $cps
    ): self
    {
        $model = new static;

        $model->productId = $productId;
        $model->orderCount = $orderCount;
        $model->totalCost = $totalCost;
        $model->unitCost = $unitCost;
        $model->cps = $cps;

        return $model;
    }
}