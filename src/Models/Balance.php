<?php

declare(strict_types=1);

namespace Friendz\Orderz\Models;

use Carbon\Carbon;

class Balance
{
    /**
     * @var string
     */
    public $serviceName;

    /**
     * @var float
     */
    public $balance;

    /**
     * @var Carbon
     */
    public $date;

    /**
     * @param string $serviceName
     * @param float $balance
     * @param Carbon $date
     * @return Balance
     */
    public static function make(string $serviceName, float $balance, Carbon $date): self
    {
        $model = new static;

        $model->serviceName = $serviceName;
        $model->balance = $balance;
        $model->date = $date;

        return $model;
    }
}