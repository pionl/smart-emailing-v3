<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Credentials;

use SmartEmailing\v3\Endpoints\Credentials\Credentials;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsRequest;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CredentialsTestCase extends ApiStubTestCase
{
    protected CredentialsRequest $credentials;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->credentials = new CredentialsRequest($this->apiStub);
    }

    /**
     * Tests the endpoint and options in the Credentials class
     */
    public function testEndpointAndOptions(): void
    {
        $expectedResponse = $this->createClientResponse('{
            "status": "ok",
            "meta": [
            ],
            "message": "Hi there! Your credentials are valid!",
            "account_id": 2
        }');
        $this->expectClientRequest('check-credentials', 'GET', null, $expectedResponse);
        $this->credentials->send();
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend(): void
    {
        $this->expectClientResponse('{
            "status": "ok",
            "meta": [],
            "message": "Hi there! Your credentials are valid!",
            "account_id": 2
        }');
        $response = $this->credentials->send();
        $this->assertResponse($response, 'Hi there! Your credentials are valid!');
        $this->assertEquals(2, $response->accountId());
    }
}
