<?php


namespace Savch\SendgridBundle\Api;

use Savch\SendgridBundle\HttpAdapter\HttpAdapterInterface;
use Savch\SendgridBundle\Api\WebApi as ApiWebApi;
use Savch\SendgridBundle\Api\Request;

/**
 * @author Andriy Savchenko andriy.savchenko@gmail.com
 */
class WebApi
{
    /**
     * @var \Savch\SendgridBundle\HttpAdapter\HttpAdapterInterface
     */
    protected $httpAdapter;

    protected $baseUrl = 'https://sendgrid.com/api/';

    protected $apiUser;

    protected $apiKey;

    protected $format = 'json';

    public function __construct(HttpAdapterInterface $httpAdapter, $apiUser, $apiKey)
    {
        $this->httpAdapter = $httpAdapter;
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
    }

    public function setFormat($format)
    {
        if (!in_array($format, array('xml', 'json'))) {
            throw new \InvalidArgumentException('Only xml or json formats are supported, provided "' .  $format . '"');
        }
        $this->format = $format;
    }

    public function getUnsubscribes(ApiWebApi\UnsubscribesGetRequest $request)
    {
        $module = "unsubscribes.get";
        return $this->makeCall($module, $request);
    }

    public function addUnsubscribes(ApiWebApi\UnsubscribesAddRequest $request)
    {
        $module = "unsubscribes.add";
        return $this->makeCall($module, $request);
    }

    public function deleteUnsubscribes(ApiWebApi\UnsubscribesDeleteRequest $request)
    {
        $module = "unsubscribes.delete";
        return $this->makeCall($module, $request);
    }

    protected function makeCall($module, Request $request)
    {
        $data = $request->toArray();
        $data['api_user'] = $this->apiUser;
        $data['api_key'] = $this->apiKey;
        $responseContent = $this->httpAdapter->submit($this->baseUrl . $module . '.' . $this->format, $data);
        $response = new Response($responseContent, $this->format);
        return $response;
    }
}