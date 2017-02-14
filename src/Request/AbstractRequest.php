<?php
namespace SmartEmailing\v3\Request;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\RequestException;

abstract class AbstractRequest
{
    /**
     * @var Api
     */
    private $api;

    /**
     * Creates the abstract endpoint that will call the methods.
     *
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * @return Api
     */
    public function api()
    {
        return $this->api;
    }

    /**
     * Returns the request method
     * @return string
     */
    protected function method()
    {
        return 'GET';
    }

    /**
     * Returns the request uri endpoint
     * @return string
     */
    abstract protected function endpoint();

    /**
     * Returns the request options that will be sent to the endpoint.
     * @return array
     */
    abstract protected function options();

    /**
     * Sends the request and builds the response
     *
     * @return Response
     * @throws RequestException
     */
    public function send()
    {
        try {
            // Send the request and handle the Guzzle exception
            $response = $this->api()->client()->request(
                $this->method(), $this->endpoint(), $this->options()
            );

            // Convert the response to internal response object
            $internalResponse = $this->createResponse($response);

            // Check if the response has an error and throw exception
            $this->handleErrorResponseStatus($internalResponse);

            // Pass the response
            return $internalResponse;
        } catch (GuzzleRequestException $exception) {
            throw $this->convertGuzzleException($exception, false);
        }
    }

    /**
     * Converts the Guzzles RequestException into internal exception
     * @param GuzzleRequestException $exception
     *
     * @return RequestException
     */
    protected function convertGuzzleException(GuzzleRequestException $exception)
    {
        // Convert to internal exception
        $response = $this->createResponse($exception->getResponse());
        $message = $exception->getMessage();

        // Use the message from API or from Guzzle exception when not fully readable and we have json
        // message
        if ($message === 'Client error' && is_string($response->message())) {
            $message = "Client error: {$response->message()}";
        }

        // Throw an exception
        return new RequestException(
            $response, $exception->getRequest(), $message, $exception->getCode(), $exception
        );
    }

    /**
     * If response has error status then creates an RequestException with the response message
     *
     * @param Response $response
     *
     * @throws RequestException
     */
    protected function handleErrorResponseStatus(Response $response)
    {
        // If there is error, lets throw an exception
        if ($response->status() === Response::ERROR) {
            $errorMessage = $response->message();
            throw new RequestException($response, null, "Client error: {$errorMessage}", $response->statusCode());
        }
    }

    /**
     * Builds the internal response
     *
     * @param ResponseInterface|null $response
     *
     * @return Response
     */
    protected function createResponse($response)
    {
        return new Response($response);
    }
}