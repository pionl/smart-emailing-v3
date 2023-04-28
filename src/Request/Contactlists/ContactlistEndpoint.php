<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Contactlists;

use SmartEmailing\v3\Api;

class ContactlistEndpoint
{
    private Api $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function lists(): Contactlists
    {
        return new Contactlists($this->api);
    }

    public function get(int $listId): Contactlist
    {
        return new Contactlist($this->api, $listId);
    }

    public function truncate(int $listId): ContactlistTruncate
    {
        return new ContactlistTruncate($this->api, $listId);
    }
}
