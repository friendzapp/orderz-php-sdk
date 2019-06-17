<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $message = 'Api Exception!';
}