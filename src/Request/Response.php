<?php
namespace SmartEmailing\v3\Request;


use Psr\Http\Message\ResponseInterface;

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
     * @var array
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
     * @var array|null
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

        $json = json_decode($response->getBody(), true);

        // If we have valid json, lets get the data
        if (is_array($json) && isset($json['status'])) {
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
     * @return array
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
     * @param array      $array
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    protected function value(array $array, $key, $default = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        return $default;
    }
    //endregion
}