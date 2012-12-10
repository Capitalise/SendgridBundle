<?php

namespace Savch\SendgridBundle\Api\WebApi;

use Savch\SendgridBundle\Api\Request;

class UnsubscribesDeleteRequest extends Request
{
    /**
     * Optional date to start retrieving for.
     *
     * @var string Date must be in YYYY-mm-dd format and be before the end_date parameter.
     */
    protected $startDate;

    /**
     * Optional date to end retrieving for.
     *
     * @var string Date must be in YYYY-mm-dd format and be after the start_date parameter
     */
    protected $endDate;

    /**
     * Unsubscribed email address to remove.
     *
     * @var string Must be a valid user account email
     */
    protected $email;

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
}