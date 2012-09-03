<?php

namespace Savch\SendgridBundle\Api;

use \ReflectionClass, \ReflectionProperty;

class Request
{
    /**
     * Should null values be included into request?
     *
     * @var bool
     */
    protected $includeNull = false;

    /**
     * Builds array representation of the request
     *
     * @return array
     */
    public function toArray()
    {
        $return = array();
        $reflect = new ReflectionClass($this);
        foreach ($reflect->getProperties(ReflectionProperty::IS_PROTECTED) as $prop) {
            $key = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $prop->getName()));
            if (!is_null($this->{$prop->getName()}) && 'includeNull' != $prop->getName()) {
                $return[$key] = $this->{$prop->getName()};
            }
        }
        return $return;
    }

    /**
     * @param boolean $includeNull
     */
    public function setIncludeNull($includeNull)
    {
        $this->includeNull = $includeNull;
    }

    /**
     * @return boolean
     */
    public function getIncludeNull()
    {
        return $this->includeNull;
    }
}