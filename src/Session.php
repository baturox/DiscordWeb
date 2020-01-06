<?php

namespace DiscordPHP;

/**
 * Class Session
 * @package DiscordPHP
 */
class Session
{
    protected $accessToken = '';
    protected $clientId = '';
    protected $clientSecret = '';
    protected $expirationTime = 0;
    protected $redirectUri = '';
    protected $refreshToken = '';
    protected $request = null;
    protected $scops = [];

    /**
     * Session constructor.
     * @param $clientId
     * @param $clientSecret
     * @param string $redirectUri
     * @param $scops
     * @param null $request
     */
    public function __construct($clientId, $clientSecret, $redirectUri = '', $scops, $request = null)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setRedirectUri($redirectUri);
        $this->setScops($scops);
        $this->request = $request ?: new Request();
    }

    /**
     * @return string
     */
    public function getAuthorizeUrl()
    {
        $parameters = [
            'client_id' => $this->getClientId(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope' => $this->getScops()
        ];
        return Request::API_URL . '/oauth2/authorize?' . http_build_query($parameters);
    }


    /**
     * @param $authorizationCode
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestAccessToken($authorizationCode)
    {
        $parameters = [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
            'scope' => $this->getScops()
        ];
        $response = $this->request->send('POST', 'oauth2/token', $parameters);

        if (isset($response->refresh_token) && isset($response->access_token)) {
            $this->setRefreshToken($response->refresh_token);
            $this->setAccessToken($response->access_token);
            $this->setExpirationTime(time() + $response->expires_in);
            $this->setScops(isset($response->scope) ? $response->scope : $this->scope);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return array
     */
    public function getScops()
    {
        return $this->scops;
    }

    /**
     * @param array $scops
     */
    public function setScops($scops)
    {
        if(!$this->scops){
            $this->scops = !empty($scops) ? implode(' ', $scops) : null;
        }
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return int
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     */
    public function setExpirationTime($expirationTime)
    {
        $this->expirationTime = $expirationTime;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

}