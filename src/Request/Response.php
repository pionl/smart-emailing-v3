<?php
namespace SmartEmailing\v3\Request;


use Psr\Http\Message\ResponseInterface;
use stdClass;

class Response
{
    const ERROR = 'error';
    const SUCCESS = 'ok';
    const CREATED = 'created';

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var array|null
     */
    protected $data = null;

    /**
     * @var stdClass|null
     */
    protected $meta = [];

    /**
     * @var string|null
     */
    protected $message = null;

    /**
     * @var string
     */
    protected $status = self::ERROR;

    /**
     * @var stdClass|null
     */
    protected $json = null;

    /**
     * @param ResponseInterface|null $response
     */
    public function __construct($response)
    {
        $this->response = $response;

        if ($response == null) {
            return;
        }

        $json = json_decode($response->getBody());

        // If we have valid json, lets get the data
        if (is_object($json) && property_exists($json, 'status')) {
            $this->json = $json;

            // Fill the data
            $this->setupData();
        }
    }

    /**
     * When json is valid, setups the data
     * @return $this
     */
    protected function setupData()
    {
        return $this->set('status')
            ->set('meta')
            ->set('message');
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
     * Response meta list
     *
     * @return stdClass|null
     */
    public function meta()
    {
        return $this->meta;
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
     * @return ResponseInterface
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Response data
     *
     * @return array|null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Fully decoded json if avail.
     * @return array|mixed|null
     */
    public function json()
    {
        return $this->json;
    }

    /**
     * Checks if status is success
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === self::SUCCESS || $this->status === self::CREATED;
    }

    /**
     * Returns the status code from guzzle response
     * @return int
     */
    public function statusCode()
    {
        if (is_null($this->response())) {
            return 500;
        }
        return $this->response()->getStatusCode();
    }

    //endregion

    //region Helpers
    /**
     * Sets the property by given key with a value from current json (using the same key). If not found
     * uses the current property value.
     *
     * @param string      $key the key in array and a propertyName if not provided
     * @param string|null $propertyName
     *
     * @return $this
     */
    protected function set($key, $propertyName = null)
    {
        if (is_null($propertyName)) {
            $propertyName = $key;
        }

        $this->{$propertyName} = $this->value($this->json, $key, $this->{$propertyName});
        return $this;
    }

    /**
     * Tries to get data from given array
     *
     * @param stdClass   $object
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