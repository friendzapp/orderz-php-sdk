<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

class Order
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $externalId;

    /**
     * @var float
     */
    public $discount_percentage;

    /**
     * @var float
     */
    public $cost;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $results;

    /**
     * @param int $id
     * @param string $externalId
     * @param string $status
     * @param array $results
     * @return Order
     */
    public static function make(int $id, string $externalId, float $discount_percentage, float $cost, string $status, array $results)
    {
        $model =  new static();

        $model->id = $id;
        $model->externalId = $externalId;
        $model->discount_percentage = $discount_percentage;
        $model->cost = $cost;
        $model->status = $status;
        $model->results = $results;

        return $model;
    }

    public const STATUS_PENDING     = 'pending';    // The order in pending to be processed
    public const STATUS_PROCESSED   = 'processed';  // The order has been processed
    public const STATUS_FAILED      = 'failed';     // The order was not processed due to an error
    public const STATUS_NO_PROCESS  = 'no_process'; // The order was not processed and will not be processed
}