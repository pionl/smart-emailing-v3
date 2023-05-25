<?php

declare(strict_types=1);

namespace SmartEmailing\v3;

use GuzzleHttp\Client;
use SmartEmailing\v3\Endpoints\Contactlists\ContactlistEndpoint;
use SmartEmailing\v3\Endpoints\Contacts\ContactsEndpoint;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsRequest;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsResponse;
use SmartEmailing\v3\Endpoints\CustomFields\CustomFieldsEndpoint;
use SmartEmailing\v3\Endpoints\CustomRequest\CustomRequest;
use SmartEmailing\v3\Endpoints\Email\EmailsEndpoint;
use SmartEmailing\v3\Endpoints\Eshops\EshopOrdersBulkRequest;
use SmartEmailing\v3\Endpoints\Eshops\EshopOrdersRequest;
use SmartEmailing\v3\Endpoints\IdentifierResponse;
use SmartEmailing\v3\Endpoints\Import\Contacts\ImportContactsRequest;
use SmartEmailing\v3\Endpoints\Import\Orders\ImportOrdersRequest;
use SmartEmailing\v3\Endpoints\Newsletter\NewsletterRequest;
use SmartEmailing\v3\Endpoints\Ping\PingRequest;
use SmartEmailing\v3\Endpoints\Send\BulkCustomEmails\BulkCustomEmailsRequest;
use SmartEmailing\v3\Endpoints\Send\BulkCustomSms\BulkCustomSmsRequest;
use SmartEmailing\v3\Endpoints\Send\TransactionalEmails\TransactionalEmailsRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;

/**
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
     */
    public function importRequest(): ImportContactsRequest
    {
        return new ImportContactsRequest($this);
    }

    /**
     * Creates new import orders request
     */
    public function importOrdersRequest(): ImportOrdersRequest
    {
        return new ImportOrdersRequest($this);
    }

    /**
     * Creates new contactlists proxy
     */
    public function contactlist(): ContactlistEndpoint
    {
        return new ContactlistEndpoint($this);
    }

    /**
     * Creates new contacts proxy
     */
    public function contacts(): ContactsEndpoint
    {
        return new ContactsEndpoint($this);
    }

    /**
     * Creates new email proxy
     */
    public function email(): EmailsEndpoint
    {
        return new EmailsEndpoint($this);
    }

    public function newsletter(int $emailId, array $contactLists): IdentifierResponse
    {
        return (new NewsletterRequest($this, $emailId, $contactLists))->send();
    }

    public function ping(): StatusResponse
    {
        return (new PingRequest($this))->send();
    }

    public function customRequest(string $action, string $method = 'GET', array $postData = []): CustomRequest
    {
        return new CustomRequest($this, $action, $method, $postData);
    }

    public function credentials(): CredentialsResponse
    {
        return (new CredentialsRequest($this))->send();
    }

    public function customFields(): CustomFieldsEndpoint
    {
        return new CustomFieldsEndpoint($this);
    }

    /**
     * @deprecated Use import-orders instead
     */
    public function eshopOrders(): EshopOrdersRequest
    {
        return new EshopOrdersRequest($this);
    }

    /**
     * @deprecated Use import-orders instead
     */
    public function eshopOrdersBulk(): EshopOrdersBulkRequest
    {
        return new EshopOrdersBulkRequest($this);
    }

    public function customEmailsBulkRequest(): BulkCustomEmailsRequest
    {
        return new BulkCustomEmailsRequest($this);
    }

    public function customSmsBulkRequest(): BulkCustomSmsRequest
    {
        return new BulkCustomSmsRequest($this);
    }

    public function transactionalEmailsRequest(): TransactionalEmailsRequest
    {
        return new TransactionalEmailsRequest($this);
    }
}
