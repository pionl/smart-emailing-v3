<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Api;

abstract class AbstractEndpoint
{
    protected Api $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }
}
