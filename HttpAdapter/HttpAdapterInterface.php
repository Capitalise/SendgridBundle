<?php

namespace Savch\SendgridBundle\HttpAdapter;

/**
 * @author Andriy Savchenko andriy.savchenko@gmail.com
 */
interface HttpAdapterInterface
{
    /**
     * Returns the content fetched from a given URL.
     *
     * @param $url
     * @param array $parameters
     * @return string
     */
    function submit($url, array $parameters = array());

    /**
     * Returns the name of the HTTP Adapter.
     *
     * @return string
     */
    function getName();
}
