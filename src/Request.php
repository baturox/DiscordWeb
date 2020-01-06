<?php

namespace DiscordPHP;

use GuzzleHttp\Client;

/**
 * Class Request
 * @package DiscordPHP
 */
class Request
{
    /**
     *
     */
    const API_URL = 'https://discordapp.com/api/v6/';
    /**
     * @var Client
     */
    private $client;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Request::API_URL
        ]);
    }

    /**
     * @param $code
     * @param $reason
     * @throws \Exception
     */
    protected function throwException($code, $reason)
    {
        throw new \Exception("{$code} => {$reason}.");
    }

    /**
     * @param $response
     * @return mixed
     * @throws \Exception
     */
    protected function returnResponse($response)
    {
        if ($response->getStatusCode() != 200) {
            $this->throwException($response->getStatusCode(), $response->getReasonPhrase());
        } else {
            $response = json_decode($response->getBody());
            if (isset($response->ErrorStatus)) {
                $this->throwException($response->ErrorStatus->Code, $response->ErrorStatus->Message);
            }

            return $response;
        }
    }

    /**
     * @param $method
     * @param $url
     * @param array $parameters
     * @param array $headers
     * @return mixed|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($method, $url, $parameters = [], $headers = [])
    {
        $response = $this->client->request($method, $url, [
            'form_params' => $parameters,
            'headers' => $headers
        ]);

        return $this->returnResponse($response);
    }
}