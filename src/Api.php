<?php

declare(strict_types=1);

namespace SmartEmailing\v3;

use GuzzleHttp\Client;
use SmartEmailing\v3\Request\Contactlists\ContactlistEndpoint;
use SmartEmailing\v3\Request\Credentials\Credentials;
use SmartEmailing\v3\Request\CustomFields\CustomFields;
use SmartEmailing\v3\Request\CustomRequest\CustomRequest;
use SmartEmailing\v3\Request\Email\EmailsEndpoint;
use SmartEmailing\v3\Request\Eshops\EshopOrders;
use SmartEmailing\v3\Request\Eshops\EshopOrdersBulk;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Newsletter\Newsletter;
use SmartEmailing\v3\Request\Ping\Ping;
use SmartEmailing\v3\Request\Send\BulkCustomEmails;
use SmartEmailing\v3\Request\Send\BulkCustomSms;
use SmartEmailing\v3\Request\Send\TransactionalEmails;

/**
 * Class Api
 *
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Tests-Aliveness_test
 */
class Api
{
    /**
     * The final API endpoint
     */
    private string $apiUrl;

    private Client $client;

    public function __construct(string $username, string $apiKey, string $apiUrl = null)
    {
        $this->apiUrl = $apiUrl ?? 'https://app.smartemailing.cz/api/v3/';
        $this->client = new Client([
            'auth' => [$username, $apiKey],
            'base_uri' => $this->apiUrl,
        ]);
    }

    /**
     * Returns current API client with auth setup and base URL
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * Creates new import request
     *
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

    public function ping(): Ping
    {
        return new Ping($this);
    }

    public function customRequest(string $action, string $method = 'GET', array $postData = []): CustomRequest
    {
        return new CustomRequest($this, $action, $method, $postData);
    }

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

    public function customEmailsBulk(): BulkCustomEmails
    {
        return new BulkCustomEmails($this);
    }

    public function customSmsBulk(): BulkCustomSms
    {
        return new BulkCustomSms($this);
    }

    public function transactionalEmails(): TransactionalEmails
    {
        return new TransactionalEmails($this);
    }
}
