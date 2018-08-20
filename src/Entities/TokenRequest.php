<?php

namespace Sails\Api\Client\Entities;

class TokenRequest
{
    private $clientId;

    private $clientSecret;

    private $grantType;

    private $audience;

    /**
     * TokenRequest constructor.
     *
     * @param        $clientId
     * @param        $clientSecret
     * @param string $audience
     * @param string $grantType
     */
    public function __construct(
        $clientId,
        $clientSecret,
        $audience,
        $grantType = 'client_credentials'
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->setGrantType($grantType);
        $this->setAudience($audience);
    }

    /**
     * @param string $grantType
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @param string $audience
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $this->grantType,
            'audience' => $this->audience,
        ];
    }
}
