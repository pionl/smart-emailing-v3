<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Ping;

use SmartEmailing\v3\Endpoints\Ping\Ping;
use SmartEmailing\v3\Endpoints\Ping\PingRequest;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class PingTestCase extends ApiStubTestCase
{
    protected PingRequest $ping;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->ping = new PingRequest($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpointAndOptions(): void
    {
        $this->createEndpointTest($this->ping, 'ping');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend(): void
    {
        $this->createSendResponse($this->ping, '{
               "status": "ok",
               "meta": [
               ],
               "message": "Hi there! API version 3 here!"
           }', 'Hi there! API version 3 here!');
    }
}
