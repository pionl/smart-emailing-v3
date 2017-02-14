<?php
namespace SmartEmailing\v3\Exceptions;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Psr\Http\Message\RequestInterface;
use SmartEmailing\v3\Request\Response;

class RequestException extends \RuntimeException
{
    /** @var RequestInterface|null */
    private $request;

    /** @var Response */
    private $response;

    /**
     * RequestException constructor.
     *
     * @param Response              $response
     * @param RequestInterface|null $request
     * @param string|null           $message
     * @param int                   $code
     * @param \Exception|null       $exception
     */
    public function __construct(Response $response, RequestInterface $request = null, $message = null, $code = 0,
                                $exception = null)
    {
        $this->response = $response;
        $this->request = $request;

        parent::__construct($message, $code, $exception);
    }

    /**
     * @return Response
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