<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Requests;

use Carbon\Carbon;

class ProductsSummary
{
    /**
     * @var array|null
     */
    public $productsToInclude;

    /**
     * @var array|null
     */
    public $productsToExclude;

    /**
     * @var Carbon
     */
    public $startDate;

    /**
     * @var Carbon
     */
    public $endDate;

    /**
     * @return array
     */
    function toArray(): array
    {
        $result = [
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d')
        ];

        if ($this->productsToInclude) {
            $result['included_products'] = $this->productsToInclude;
        }

        if ($this->productsToExclude) {
            $result['excluded_products'] = $this->productsToExclude;
        }

        return $result;
    }
}