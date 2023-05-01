<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Email;

use SmartEmailing\v3\Endpoints\AbstractEndpoint;
use SmartEmailing\v3\Endpoints\Email\Create\EmailCreateRequest;

class EmailsEndpoint extends AbstractEndpoint
{
    public function createRequest(string $title): EmailCreateRequest
    {
        return new EmailCreateRequest($this->api, $title);
    }
}
