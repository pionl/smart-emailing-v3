<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

use Psr\Http\Message\RequestInterface;
use SmartEmailing\v3\Endpoints\AbstractResponse;

class RequestException extends \RuntimeException
{
    private ?RequestInterface $request = null;

    private AbstractResponse $response;

    public function __construct(
        AbstractResponse $response,
        RequestInterface $request = null,
        ?string $message = null,
        int $code = 0,
        ?\Exception $exception = null
    ) {
        $this->response = $response;
        $this->request = $request;

        parent::__construct($message ?? 'RequestException', $code, $exception);
    }

    public function response(): AbstractResponse
    {
        return $this->response;
    }

    public function request(): ?RequestInterface
    {
        return $this->request;
    }
}
