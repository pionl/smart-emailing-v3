<?php
namespace SmartEmailing\v3\Tests\TestCase;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Api;

abstract class BaseTestCase extends TestCase
{
    protected $username;
    protected $apiKey;

    protected $canDoLiveTest = false;

    /**
     * Constructs a test case with the given name. Setups default api-key/username
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // Ignore the non-existing env file
        try {
            // Load the Env variables
            $dotEnv = new Dotenv(__DIR__.'/../../');
            $dotEnv->load();
            $this->canDoLiveTest = true;
        } catch (\Exception $exception) {
        }
        
        // Setup the username/api-key
        $this->username = $this->env('USERNAME', 'username');
        $this->apiKey = $this->env('API_KEY', 'password');
    }

    /**
     * Creates new API
     *
     * @param string|null $apiUrl
     *
     * @return Api
     */
    protected function createApi($apiUrl = null)
    {
        return new Api($this->username, $this->apiKey, $apiUrl);
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        return $value;
    }
}
