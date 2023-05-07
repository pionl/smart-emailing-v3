<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Credentials;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Endpoints\Credentials\Credentials;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsRequest;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsResponse;
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
        $this->defaultReturnResponse = Utils::streamFor('{
            "status": "ok",
            "meta": [
            ],
            "message": "Hi there! Your credentials are valid!",
            "account_id": 2
        }');
        $this->createEndpointTest($this->credentials, 'check-credentials');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend(): void
    {
        /** @var CredentialsResponse $response */
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
