<?php
namespace SmartEmailing\v3\Tests;

use GuzzleHttp\Psr7\Uri;

class ApiClientTest extends BaseTestCase
{
    /**
     * Tests the client creation
     */
    public function testConstruct()
    {
        $this->assertNotNull($this->createApi()->client(), 'The api client must be created');
    }

    /**
     * Tests if the client config has auth data
     */
    public function testAuthConfig()
    {
        $auth = $this->createApi()->client()->getConfig('auth');

        $this->assertEquals([$this->username, $this->apiKey], $auth,
            'The username and api-key are not same in http client');
    }

    /**
     * Tests the default base URL
     */
    public function testDefaultBaseUri()
    {
        /** @var Uri $baseUri */
        $baseUri = $this->createApi()->client()->getConfig('base_uri');

        // Check if the URL is same
        $this->assertEquals('https://app.smartemailing.cz/api/v3/', $baseUri);
    }

    /**
     * Tests the default base URL
     */
    public function testCustomBaseUri()
    {
        /** @var Uri $baseUri */
        $baseUri = $this->createApi('test')->client()->getConfig('base_uri');

        // Check if the URL is same
        $this->assertEquals('test', $baseUri);
    }
}