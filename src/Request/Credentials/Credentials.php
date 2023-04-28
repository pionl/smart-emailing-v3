<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Credentials;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Request\AbstractRequest;

/**
 * @extends AbstractRequest<Response>
 */
class Credentials extends AbstractRequest
{
    protected function endpoint(): string
    {
        return 'check-credentials';
    }

    protected function options()
    {
        return [];
    }

    /**
     * @param ResponseInterface|null $response
     *
     * @return Response
     */
    protected function createResponse($response)
    {
        return new Response($response);
    }
}
