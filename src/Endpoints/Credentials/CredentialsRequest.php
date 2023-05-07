<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Credentials;

use Psr\Http\Message\ResponseInterface;
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

    protected function createResponse(?ResponseInterface $response): CredentialsResponse
    {
        return new CredentialsResponse($response);
    }
}
