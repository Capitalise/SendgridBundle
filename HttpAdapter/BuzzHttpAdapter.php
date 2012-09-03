<?php

namespace Savch\SendgridBundle\HttpAdapter;

use Buzz\Browser;
use Buzz\Message\RequestInterface;

/**
 * @author Andriy Savchenko andriy.savchenko@gmail.com
 */
class BuzzHttpAdapter implements HttpAdapterInterface
{
    /**
     * @var \Buzz\Browser
     */
    protected $browser;

    /**
     * @param \Buzz\Browser $browser
     */
    public function __construct(Browser $browser = null)
    {
        if (null === $browser) {
            $this->browser = new Browser(new \Buzz\Client\Curl());
        } else {
            $this->browser = $browser;
        }
    }

    function submit($url, array $parameters = array())
    {
        $response = $this->browser->submit($url, $parameters, RequestInterface::METHOD_POST);
        return $response->getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'buzz';
    }
}
