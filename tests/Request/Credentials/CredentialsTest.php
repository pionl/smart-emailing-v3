<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Credentials;

use SmartEmailing\v3\Request\Credentials\Credentials;
use SmartEmailing\v3\Request\Credentials\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CredentialsTestCase extends ApiStubTestCase
{
    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->credentials = new Credentials($this->apiStub);
    }

    /**
     * Tests the endpoint and options in the Credentials class
     */
    public function testEndpointAndOptions()
    {
        $this->createEndpointTest($this->credentials, 'check-credentials');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        /** @var Response $response */
        $response = $this->createSendResponse($this->credentials, '{
            "status": "ok",
            "meta": [
            ],
            "message": "Hi there! Your credentials are valid!",
            "account_id": 2
        }', 'Hi there! Your credentials are valid!');

        $this->assertEquals(2, $response->accountId());
    }
}
