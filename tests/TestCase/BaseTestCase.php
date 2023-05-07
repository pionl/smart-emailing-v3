<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\TestCase;

use Dotenv\Dotenv;
use Exception;
use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Api;

abstract class BaseTestCase extends TestCase
{
    protected string $username;

    protected string $apiKey;

    protected bool $canDoLiveTest = false;

    /**
     * Constructs a test case with the given name. Setups default api-key/username
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // Ignore the non-existing env file
        try {
            // Load the Env variables
            $dotEnv = new Dotenv(__DIR__ . '/../../');
            $dotEnv->load();
            $this->canDoLiveTest = true;
        } catch (Exception $exception) {
        }

        // Setup the username/api-key
        $this->username = $this->env('USERNAME', 'username');
        $this->apiKey = $this->env('API_KEY', 'password');
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function env(string $key, $default = null)
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

    /**
     * Creates new API
     */
    protected function createApi(?string $apiUrl = null): Api
    {
        return new Api($this->username, $this->apiKey, $apiUrl);
    }
}
