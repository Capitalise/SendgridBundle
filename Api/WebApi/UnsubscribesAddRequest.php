<?php

namespace Savch\SendgridBundle\Api\WebApi;

use Savch\SendgridBundle\Api\Request;

class UnsubscribesAddRequest extends Request
{
    /**
     * Email address to add to unsubscribe list
     *
     * @var Must be a valid email address
     */
    protected $email;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}