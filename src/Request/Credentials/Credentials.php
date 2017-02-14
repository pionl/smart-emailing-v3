<?php
namespace SmartEmailing\v3\Request\Credentials;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Request\AbstractRequest;

class Credentials extends AbstractRequest
{
    protected function endpoint()
    {
        return 'check-credentials';
    }

    protected function options()
    {
        return [];
    }

    /**
     * @return Response
     */
    public function send()
    {
        return parent::send();
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