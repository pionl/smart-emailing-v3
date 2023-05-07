<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractResponse
{
    public const ERROR = 'error';
    public const SUCCESS = 'ok';
    public const CREATED = 'created';

    protected ?string $message = null;

    protected string $status = self::ERROR;

    protected ?\stdClass $json = null;

    private ?ResponseInterface $response;

    public function __construct(?ResponseInterface $response)
    {
        $this->response = $response;

        if ($response === null) {
            return;
        }

        try {
            $json = json_decode((string) $response->getBody(), null, 512, JSON_THROW_ON_ERROR);

            // If we have valid json, lets get the data
            if ($json instanceof \stdClass && property_exists($json, 'status')) {
                $this->json = $json;

                // Fill the data
                $this->setupData();
            }
        } catch (\JsonException $jsonException) {
            $this->json = null;
            $this->status = self::ERROR;
        }
    }

    /**
     * Response message
     */
    public function message(): ?string
    {
        return $this->message;
    }

    /**
     * An error/success status
     */
    public function status(): string
    {
        return $this->status;
    }

    public function response(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * Fully decoded json if avail.
     */
    public function json(): ?\stdClass
    {
        return $this->json;
    }

    /**
     * Checks if status is success
     */
    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS || $this->status === self::CREATED;
    }

    /**
     * Returns the status code from guzzle response
     */
    public function statusCode(): int
    {
        if ($this->response() === null) {
            return 500;
        }

        return $this->response()
            ->getStatusCode();
    }

    /**
     * When json is valid, setups the data
     *
     * @return $this
     */
    protected function setupData()
    {
        return $this->set('status')
            ->set('message');
    }

    /**
     * Sets the property by given key with a value from current json (using the same key). If not found uses the current
     * property value.
     *
     * @param string      $key the key in array and a propertyName if not provided
     *
     * @return $this
     */
    protected function set(string $key, ?string $propertyName = null)
    {
        if ($propertyName === null) {
            $propertyName = $key;
        }

        $this->{$propertyName} = $this->value($this->json, $key, $this->{$propertyName} ?? null);
        return $this;
    }

    /**
     * Tries to get data from given array
     *
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    protected function value(?\stdClass $object, string $key, $default = null)
    {
        if ($object === null) {
            return $default;
        }
        if (property_exists($object, $key)) {
            return $object->{$key};
        }

        return $default;
    }
}
