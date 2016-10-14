<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs;

use GuzzleHttp\Client;
use Ittoolspl\Smslabs\Exception\InvalidResponseException;

class HttpClient
{
    const API_URL = 'https://api.smslabs.net.pl/apiSms';

    /**
     * @var string
     */
    private $appKey = null;

    /**
     * @var string
     */
    private $secretKey = null;

    /**
     * @var Client
     */
    private $client = null;

    /**
     * HttpClient constructor.
     * @param string $appKey
     * @param string $secretKey
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->appKey    = $appKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $method
     * @return array
     * @throws InvalidResponseException
     */
    public function sendRequest(string $url, array $data = null, string $method = 'GET') : array
    {
        if (!$this->validateHttpMethod($method)) {
            throw new \InvalidArgumentException('Invalid method');
        }

        $response = $this->getClient()->request($method, self::API_URL.$url, [
            'auth' => [$this->appKey, $this->secretKey],
            'form_params' => $data,
        ]);

        if ($response->getStatusCode() != 200) {
            throw new InvalidResponseException('Invalid HTTP code');
        }

        return $this->parseJsonData((string)$response->getBody());
    }

    /**
     * @param string $json
     * @return array
     * @throws InvalidResponseException
     */
    private function parseJsonData(string $json) : array
    {
        try {
            $bodyArr = \GuzzleHttp\json_decode($json, true);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidResponseException('Invalid JSON data');
        }

        if (!array_key_exists('data', $bodyArr)) {
            throw new InvalidResponseException('Missing data array key in response');
        }

        return $bodyArr['data'];
    }

    /**
     * @param string $method
     * @return bool
     */
    private function validateHttpMethod(string $method) : bool
    {
        return in_array($method, ['POST', 'PUT', 'GET', 'DELETE']);
    }

    /**
     * @return Client
     */
    public function getClient() : Client
    {
        if ($this->client === null) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @param Client $client
     * @return HttpClient $this
     */
    public function setClient(Client $client) : HttpClient
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppKey() : string
    {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     * @return HttpClient $this
     */
    public function setAppKey(string $appKey) : HttpClient
    {
        $this->appKey = $appKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey() : string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @return HttpClient $this
     */
    public function setSecretKey(string $secretKey) : HttpClient
    {
        $this->secretKey = $secretKey;

        return $this;
    }
}
