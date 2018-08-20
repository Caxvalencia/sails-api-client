<?php

namespace Sails\Api\Client\Entities;

class TokenResponse
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $expiresIn;

    /** @var string */
    private $tokenType;

    /** @var string */
    private $requestedAt;

    /**
     * TokenResponse constructor.
     *
     * @param $accessToken
     * @param $expiresIn
     * @param $tokenType
     */
    public function __construct($accessToken, $expiresIn, $tokenType)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
        $this->requestedAt = date('c');
    }

    /**
     * @param $data
     *
     * @return TokenResponse
     */
    public static function fromArray($data)
    {
        return new self(
            $data['access_token'],
            $data['expires_in'],
            $data['token_type']
        );
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }
}
