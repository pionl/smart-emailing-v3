<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Email;

use SmartEmailing\v3\Api;

class EmailsEndpoint
{

    /** @var Api */
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function createNew(string $title): Email
    {
        return new Email($this->api, $title);
    }

}
