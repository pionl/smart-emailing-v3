<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Truncate;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;

/**
 * @extends AbstractRequest<StatusResponse>
 */
class ContactlistTruncateRequest extends AbstractRequest
{
    private int $listId;

    public function __construct(Api $api, int $listId)
    {
        parent::__construct($api);

        $this->listId = $listId;
    }

    public function toArray(): array
    {
        return [];
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function endpoint(): string
    {
        return 'contactlists/' . $this->listId . '/truncate';
    }
}
