<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

use Carbon\Carbon;

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
     * @var string
     */
    public $rawDate;

    /**
     * @var Carbon|null
     */
    public $date;

    /**
     * @param int $productId
     * @param int $orderCount
     * @param float $total
     * @param float $cps
     * @param string $rawDate
     * @return ProductSummary
     */
    public static function make(
        int $productId,
        int $orderCount,
        float $total,
        float $cps,
        string $rawDate
    ): self
    {
        $model = new static;

        $model->productId = $productId;
        $model->orderCount = $orderCount;
        $model->total = $total;
        $model->cps = $cps;
        $model->rawDate = $rawDate;

        try {
            $model->date = Carbon::parse($rawDate);
        } catch (\Exception $e) {
            $model->date = null;
        }

        return $model;
    }
}