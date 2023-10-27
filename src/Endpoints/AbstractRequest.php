<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\RequestException;

/**
 * @template TResponse of AbstractResponse
 */
abstract class AbstractRequest implements \JsonSerializable
{
    private Api $api;

    /**
     * Creates the abstract endpoint that will call the methods.
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function api(): Api
    {
        return $this->api;
    }

    /**
     * Sends the request and builds the response
     *
     * @return TResponse
     */
    public function send()
    {
        try {
            // Send the request and handle the Guzzle exception
            $response = $this->api()
                ->client()
                ->request($this->method(), $this->endpoint(), $this->options());

            // Convert the response to internal response object
            $internalResponse = $this->createResponse($response);

            // Check if the response has an error and throw exception
            $this->handleErrorResponseStatus($internalResponse);

            // Pass the response
            return $internalResponse;
        } catch (GuzzleRequestException $guzzleRequestException) {
            throw $this->convertGuzzleException($guzzleRequestException);
        }
    }

    abstract public function toArray(): array;

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Returns the request method
     */
    protected function method(): string
    {
        return 'GET';
    }

    /**
     * Returns the request uri endpoint
     */
    abstract protected function endpoint(): string;

    /**
     * Returns the request options that will be sent to the endpoint.
     */
    protected function options(): array
    {
        if ($this->method() === 'GET') {
            return [
                'query' => $this->query(),
            ];
        }
        return [
            'json' => $this->jsonSerialize(),
        ];
    }

    /**
     * Sets the value into array if not valid
     *
     * @param mixed $value
     */
    protected function setQuery(array &$array, string $key, $value): void
    {
        if ($value === null) {
            return;
        }

        $array[$key] = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Converts the Guzzles RequestException into internal exception
     */
    protected function convertGuzzleException(GuzzleRequestException $exception): RequestException
    {
        // Convert to internal exception
        $response = $this->createResponse($exception->getResponse());
        $message = $exception->getMessage();

        // Use the message from API or from Guzzle exception when not fully readable and we have json
        // message
        if ($message === 'Client error' && is_string($response->message())) {
            $message = sprintf('Client error: %s', $response->message());
        }

        // Throw an exception
        return new RequestException(
            $response,
            $exception->getRequest(),
            $message,
            $exception->getCode(),
            $exception
        );
    }

    /**
     * If response has error status then creates an RequestException with the response message
     */
    protected function handleErrorResponseStatus(AbstractResponse $response): void
    {
        // If there is error, lets throw an exception
        if ($response->status() === AbstractResponse::ERROR) {
            $errorMessage = $response->message();
            throw new RequestException($response, null, sprintf(
                'Client error: %s',
                $errorMessage
            ), $response->statusCode());
        }
    }

    /**
     * Builds the internal response
     *
     * @return TResponse
     */
    protected function createResponse(?ResponseInterface $response): AbstractResponse
    {
        /** @var TResponse */
        return new StatusResponse($response);
    }
}
