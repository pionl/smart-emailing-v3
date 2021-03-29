<?php

namespace SmartEmailing\v3;

use GuzzleHttp\Client;
use SmartEmailing\v3\Request\Contactlists\ContactlistEndpoint;
use SmartEmailing\v3\Request\Credentials\Credentials;
use SmartEmailing\v3\Request\CustomFields\CustomFields;
use SmartEmailing\v3\Request\Email\EmailsEndpoint;
use SmartEmailing\v3\Request\Eshops\EshopOrders;
use SmartEmailing\v3\Request\Eshops\EshopOrdersBulk;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Newsletter\Newsletter;
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

    /** @var Client */
    private $client;

    /**
     * Api constructor.
     *
     * @param string $username
     * @param string $apiKey
     * @param string|null $apiUrl
     */
    public function __construct(string $username, string $apiKey, $apiUrl = null)
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
    public function client(): Client
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
     * Creates new contactlists proxy
     */
    public function contactlist(): ContactlistEndpoint
    {
        return new ContactlistEndpoint($this);
    }

    /**
     * Creates new email proxy
     */
    public function email(): EmailsEndpoint
    {
        return new EmailsEndpoint($this);
    }

    /**
     * Creates new contactlists proxy
     */
    public function newsletter(int $emailId, array $contactLists): Newsletter
    {
        return new Newsletter($this, $emailId, $contactLists);
    }

    /**
     * @return Ping
     */
    public function ping(): Ping
    {
        return new Ping($this);
    }

    /**
     * @return Credentials
     */
    public function credentials(): Credentials
    {
        return new Credentials($this);
    }

    public function customFields(): CustomFields
    {
        return new CustomFields($this);
    }

    public function eshopOrders(): EshopOrders
    {
        return new EshopOrders($this);
    }

    public function eshopOrdersBulk(): EshopOrdersBulk
    {
        return new EshopOrdersBulk($this);
    }

}
