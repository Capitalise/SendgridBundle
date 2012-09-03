<?php

namespace Savch\SendgridBundle\Api\Webapi;

use Savch\SendgridBundle\Api\Request;

class UnsubscribesGetRequest extends Request
{
    /**
     * Retrieve the timestamp of the unsubscribe records. It will return a date in a MySQL timestamp format - YYYY-MM-DD HH:MM:SS
     *
     * @var int Must be set to 1
     */
    protected $date;

    /**
     * Number of days in the past for which to retrieve unsubscribes (includes today)
     *
     * @var int  If specified, must be an integer greater than 0
     */
    protected $days;

    /**
     * The start of the date range for which to retrieve unsubscribes.
     *
     * @var string Date must be in YYYY-MM-DD format and be earlier than the end_date parameter.
     */
    protected $startDate;

    /**
     * The end of the date range for which to retrieve unsubscribes.
     *
     * @var string Date must be in YYYY-MM-DD format and be later than the start_date parameter.
     */
    protected $endDate;

    /**
     * Optional field to limit the number of results returned.
     *
     * @var int
     */
    protected $limit;

    /**
     * Optional beginning point in the list to retrieve from.
     *
     * @var int
     */
    protected $offset;

    /**
     * Optional email addresses to search for.
     *
     * @var string email address eg testing@example.com
     */
    protected $email;

    /**
     * @param int $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

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
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
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