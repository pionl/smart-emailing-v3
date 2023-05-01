<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Credentials;

use SmartEmailing\v3\Endpoints\AbstractRequest;

/**
 * @extends AbstractRequest<CredentialsResponse>
 */
class CredentialsRequest extends AbstractRequest
{
    public function toArray(): array
    {
        return [];
    }

    protected function endpoint(): string
    {
        return 'check-credentials';
    }

    protected function createResponse($response)
    {
        return new CredentialsResponse($response);
    }
}
