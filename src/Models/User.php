<?php

declare(strict_types=1);

namespace Friendz\Orderz\Api\Models;

class User
{
    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $lastname;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $address;

    /**
     * User constructor.
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $address
     */
    public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $address
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->address = $address;
    }

    /**
     * @return array
     */
    function toArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'address' => $this->address
        ];
    }
}