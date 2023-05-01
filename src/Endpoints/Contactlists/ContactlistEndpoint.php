<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists;

use SmartEmailing\v3\Endpoints\AbstractEndpoint;
use SmartEmailing\v3\Endpoints\Contactlists\Get\ContactlistGetRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Search\ContactlistSearchRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Truncate\ContactlistTruncateRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Models\Contactlist;

class ContactlistEndpoint extends AbstractEndpoint
{
    public function searchRequest(): ContactlistSearchRequest
    {
        return new ContactlistSearchRequest($this->api);
    }

    /**
     * @return Contactlist[]
     */
    public function list(): array
    {
        return $this->searchRequest()
            ->send()
            ->data();
    }

    public function get(int $listId): ?Contactlist
    {
        return $this->getRequest($listId)
            ->send()
            ->data();
    }

    public function getRequest(int $listId): ContactlistGetRequest
    {
        return new ContactlistGetRequest($this->api, $listId);
    }

    public function truncate(int $listId): StatusResponse
    {
        return (new ContactlistTruncateRequest($this->api, $listId))->send();
    }
}
