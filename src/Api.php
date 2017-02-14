<?php
namespace SmartEmailing\v3;

use GuzzleHttp\Client;
use SmartEmailing\v3\Request\Credentials\Credentials;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Ping\Ping;

/**
 * Class Api
 * @package SmartEmailing\v3
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Aliveness_test
 */
class Api
{
    /**
     * The final API endpoint
     * @var string
     */
    private $apiUrl;

    /**
     * Api constructor.
     *
     * @param string      $username
     * @param string      $apiKey
     * @param string|null $apiUrl
     */
    public function __construct($username, $apiKey, $apiUrl = null)
    {
        $this->apiUrl = $apiUrl;
        $this->client = new Client([
            'auth' => [$username, $apiKey],
            'base_uri' => ($apiUrl ? $apiUrl : 'https://app.smartemailing.cz/api/v3/')
        ]);
    }

    /**
     * Returns current API client with auth setup and base URL
     * @return Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Creates new import request
     * @return Import
     */
    public function import()
    {
        return new Import($this);
    }

    /**
     * @return Ping
     */
    public function ping()
    {
        return new Ping($this);
    }

    /**
     * @return Credentials
     */
    public function credentials()
    {
        return new Credentials($this);
    }

}