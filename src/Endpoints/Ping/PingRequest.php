<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Ping;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;

/**
 * @extends AbstractRequest<StatusResponse>
 */
class PingRequest extends AbstractRequest
{
    public function toArray(): array
    {
        return [];
    }

    protected function endpoint(): string
    {
        return 'ping';
    }
}
