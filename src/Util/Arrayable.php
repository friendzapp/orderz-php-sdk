<?php
declare(strict_types=1);

namespace Friendz\FdzBroker\Api\Util;

interface Arrayable
{
    /**
     * @return array
     */
    function toArray(): array;
}
