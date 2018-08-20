<?php

namespace Sails\Api\Client\Services;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
use Sails\Api\Client\Entities\TokenResponse;
use Sails\Api\Client\Exceptions\SailsException;

class Service
{
    /** @var string */
    protected $apiBaseUri;

    /** @var TokenResponse */
    protected $tokenResponse;

    /** @var Client */
    protected $httpClient;

    /** @var \Psr\SimpleCache\CacheInterface */
    protected $cache;

    /**
     * Service constructor.
     *
     * @param TokenResponse $apiBaseUri
     * @param string|null   $tokenResponse
     */
    protected function __construct($tokenResponse = null, $apiBaseUri = null)
    {
        $this->apiBaseUri = $apiBaseUri;
        $this->tokenResponse = $tokenResponse;

        $this->setHttpClient();
        $this->setCache();
    }

    protected function setCache()
    {
        $filesystemAdapter = new Local(__DIR__ . '/../../storage');
        $filesystem = new Filesystem($filesystemAdapter);

        $this->cache = new FilesystemCachePool($filesystem);
    }

    protected function setHttpClient()
    {
        $config = ['timeout' => 4.0];

        if ($this->apiBaseUri) {
            $config['base_uri'] = $this->apiBaseUri;
            $config['headers'] = $this->getHeaders();
        }

        $this->httpClient = new Client($config);
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => $this->tokenResponse->getTokenType() . ' ' . $this->tokenResponse->getAccessToken()
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array|mixed|object
     */
    protected function jsonDecodeResponse($response)
    {
        $responseContent = $response->getBody()->getContents();

        return json_decode($responseContent, true);
    }

    /**
     * @param RequestException $ex
     *
     * @return SailsException
     */
    protected function processRequestException(RequestException $ex)
    {
        if (!$ex->hasResponse()) {
            return SailsException::instance('Failed request', 500, $ex);
        }

        $response = $this->jsonDecodeResponse($ex->getResponse());

        $message = isset($response['message'])
            ? implode(', ', $response['message'])
            : $ex->getResponse()->getReasonPhrase();

        return SailsException::instance(
            $message,
            $ex->getResponse()->getStatusCode(),
            $ex
        )->setResponse($response);
    }
}
