<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

use Psr\Http\Message\RequestInterface;
use SmartEmailing\v3\Endpoints\AbstractResponse;

class RequestException extends \RuntimeException
{
    private ?RequestInterface $request = null;

    private AbstractResponse $response;

    /**
     * RequestException constructor.
     *
     * @param string|null           $message
     * @param int                   $code
     * @param \Exception|null       $exception
     */
    public function __construct(
        AbstractResponse $response,
        RequestInterface $request = null,
        $message = null,
        $code = 0,
        $exception = null
    ) {
        $this->response = $response;
        $this->request = $request;

        parent::__construct($message, $code, $exception);
    }

    /**
     * @return AbstractResponse
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @return null|RequestInterface
     */
    public function request()
    {
        return $this->request;
    }
}
