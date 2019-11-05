<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

use Friendz\Orderz\Api\Util\SecureUtility;

class OrderResult
{
    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $code;

    /**
     * @var int|null
     */
    private $clientId;

    /**
     * @var array
     */
    private $passwords = [];

    /**
     * @param string $link
     * @param string $code
     * @param array $passwords
     * @param int|null $clientId
     * @return OrderResult
     */
    public static function make(string $link, string $code, array $passwords, ?int $clientId = null)
    {
        $model = new static;

        $model->link = $link;
        $model->code = $code;
        $model->passwords = $passwords;
        $model->clientId = $clientId;

        return $model;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        if (!$this->clientId) {
            return $this->code;
        }

        if (!isset($this->passwords[$this->clientId])) {
            return $this->code;
        }

        $password = $this->passwords[$this->clientId];

        return SecureUtility::decryptAes256($password, $this->code);
    }
}