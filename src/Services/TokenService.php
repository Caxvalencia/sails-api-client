<?php

namespace Sails\Api\Client\Services;

use Illuminate\Encryption\Encrypter;
use Sails\Api\Client\Config;
use Sails\Api\Client\Entities\TokenRequest;
use Sails\Api\Client\Entities\TokenResponse;
use Sails\Api\Client\Sails;
use Sails\Api\Client\Traits\Singletonable;

class TokenService extends Service
{
    use Singletonable;

    const URL_FOR_DEVELOPMENT = 'https://sails-dev-api.auth0.com/oauth/token';

    const URL_FOR_PRODUCTION = 'https://sails-api.auth0.com/oauth/token';

    const TOKEN_RESPONSE_CACHE_ID = 'TOKEN_RESPONSE_CACHE_ID';

    /** @var TokenRequest */
    private $tokenRequest;

    /** @var Encrypter */
    private $encrypter;

    /** @var string */
    private $url;

    /**
     * TokenService constructor.
     *
     * @param $consumerId
     * @param $consumerSecret
     * @param $audience
     */
    final protected function __construct($consumerId, $consumerSecret, $audience)
    {
        parent::__construct();

        $this->url = $audience === Sails::BASE_URI_FOR_DEVELOPMENT
            ? self::URL_FOR_DEVELOPMENT
            : self::URL_FOR_PRODUCTION;

        $this->initTokenRequest($consumerId, $consumerSecret, $audience);
        $this->initEncrypter();
    }

    /**
     * @return TokenResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get()
    {
        if (!$this->cache->has(self::TOKEN_RESPONSE_CACHE_ID)) {
            $tokenResponse = $this->doTokenRequest();
            $this->addTokenToCache($tokenResponse);

            return $tokenResponse;
        }

        /** @var TokenResponse $tokenResponse */
        $tokenResponse = $this->encrypter->decrypt(
            $this->cache->get(self::TOKEN_RESPONSE_CACHE_ID)
        );

        return $tokenResponse;
    }

    /**
     * @param $consumerId
     * @param $consumerSecret
     * @param $audience
     */
    protected function initTokenRequest($consumerId, $consumerSecret, $audience)
    {
        $this->tokenRequest = new TokenRequest($consumerId, $consumerSecret, $audience);
    }

    protected function initEncrypter()
    {
        $this->encrypter = new Encrypter(
            Config::get('security.key'),
            Config::get('security.cipher', 'AES-256-CBC')
        );
    }

    /**
     * @param TokenResponse $tokenResponse
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function addTokenToCache($tokenResponse)
    {
        $this->cache->set(
            self::TOKEN_RESPONSE_CACHE_ID,
            $this->encrypter->encrypt($tokenResponse),
            $tokenResponse->getExpiresIn() / 2
        );
    }

    /**
     * @return TokenResponse
     */
    private function doTokenRequest()
    {
        $response = $this->httpClient->post(
            $this->url,
            ['json' => $this->tokenRequest->toArray()]
        );

        $responseContent = $response->getBody()->getContents();
        $tokenResponse = TokenResponse::fromArray(json_decode($responseContent, true));

        return $tokenResponse;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
