<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Ping;

use SmartEmailing\v3\Request\AbstractRequest;

class Ping extends AbstractRequest
{
    protected function endpoint(): string
    {
        return 'ping';
    }

    protected function options()
    {
        return [];
    }
}
