<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractResponse
{
    public const ERROR = 'error';
    public const SUCCESS = 'ok';
    public const CREATED = 'created';

    /**
     * @var string|null
     */
    protected $message = null;

    /**
     * @var string
     */
    protected $status = self::ERROR;

    /**
     * @var \stdClass|null
     */
    protected $json = null;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @param ResponseInterface|null $response
     */
    public function __construct($response)
    {
        $this->response = $response;

        if ($response === null) {
            return;
        }

        try {
            $json = json_decode((string) $response->getBody(), null, 512, JSON_THROW_ON_ERROR);

            // If we have valid json, lets get the data
            if (is_object($json) && property_exists($json, 'status')) {
                $this->json = $json;

                // Fill the data
                $this->setupData();
            }
        } catch (\JsonException $jsonException) {
            $this->json = null;
        }
    }

    //region Getters

    /**
     * Response message
     *
     * @return string|null
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * An error/success status
     *
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return ResponseInterface|null
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Fully decoded json if avail.
     *
     * @return array|mixed|null
     */
    public function json()
    {
        return $this->json;
    }

    /**
     * Checks if status is success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === self::SUCCESS || $this->status === self::CREATED;
    }

    /**
     * Returns the status code from guzzle response
     *
     * @return int
     */
    public function statusCode()
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

    //endregion

    //region Helpers
    /**
     * Sets the property by given key with a value from current json (using the same key). If not found uses the current
     * property value.
     *
     * @param string      $key the key in array and a propertyName if not provided
     * @param string|null $propertyName
     *
     * @return $this
     */
    protected function set($key, $propertyName = null)
    {
        if ($propertyName === null) {
            $propertyName = $key;
        }

        $this->{$propertyName} = $this->value($this->json, $key, $this->{$propertyName});
        return $this;
    }

    /**
     * Tries to get data from given array
     *
     * @param \stdClass   $object
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    protected function value($object, $key, $default = null)
    {
        if (property_exists($object, $key)) {
            return $object->{$key};
        }

        return $default;
    }

    //endregion
}
