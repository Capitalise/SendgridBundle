<?php

namespace Savch\SendgridBundle\Api;

class Response
{
    protected $rawData;

    protected $array;

    protected $format;

    public function __construct($data, $format)
    {
        $this->rawData = $data;
        $this->format = $format;
    }

    public function toArray()
    {
        if (is_null($this->array)) {
            if ('json' == $this->format) {
                $this->array = json_decode($this->rawData, true);
                if (is_null($this->array)) {
                    throw new \InvalidArgumentException('Received wrong formatted json from server');
                }
            } else {
                throw new \InvalidArgumentException('XML format is not supported');
            }
        }
        return $this->array;
    }

    public function getRawData()
    {
        return $this->rawData;
    }

    public function isSuccess()
    {
        $array = $this->toArray();
        return !isset($array['message']) || 'success' == $array['message'];
    }

    public function getErrors()
    {
        $array = $this->toArray();
        return isset($array['errors']) ? $array['errors'] : array();
    }
}