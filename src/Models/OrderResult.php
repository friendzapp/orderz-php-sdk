<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

class OrderResult
{
    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $code;

    /**
     * @param string $link
     * @param string $code
     * @return OrderResult
     */
    public static function make(string $link, string $code)
    {
        $model = new static;

        $model->link = $link;
        $model->code = $code;

        return $model;
    }
}