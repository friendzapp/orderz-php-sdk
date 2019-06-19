<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Exceptions;

use Exception;

class BaseApiException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Unexpected API Exception.';

    /**
     * @var bool
     */
    protected $shouldRetry = false;

    /**
     * BaseApiException constructor.
     * @param string $message
     * @param bool $shouldRetry
     */
    public function __construct(string $message = "", bool $shouldRetry = false)
    {
        parent::__construct($message, 0, null);

        $this->shouldRetry = $shouldRetry;
    }

    /**
     * @return bool
     */
    public function shouldRetry(): bool
    {
        return $this->shouldRetry;
    }
}