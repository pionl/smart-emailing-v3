<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts;

use SmartEmailing\v3\Endpoints\AbstractEndpoint;
use SmartEmailing\v3\Endpoints\Contacts\Get\ContactsGetRequest;
use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchRequest;
use SmartEmailing\v3\Models\Contact;

class ContactsEndpoint extends AbstractEndpoint
{
    public function searchRequest(): ContactsSearchRequest
    {
        return new ContactsSearchRequest($this->api);
    }

    /**
     * @return Contact[]
     */
    public function list(): array
    {
        return $this->searchRequest()
            ->send()
            ->data();
    }

    public function getByEmailAddress(string $emailAddress): ?Contact
    {
        $request = $this->searchRequest();
        $request->filter()
            ->byEmailAddress(trim($emailAddress));
        return $request->send()
            ->getByEmailAddress(trim($emailAddress));
    }

    public function get(int $contactId): ?Contact
    {
        return $this->getRequest($contactId)
            ->send()
            ->data();
    }

    public function getRequest(int $contactId): ContactsGetRequest
    {
        return new ContactsGetRequest($this->api, $contactId);
    }
}
