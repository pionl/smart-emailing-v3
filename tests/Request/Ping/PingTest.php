<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Ping;

use SmartEmailing\v3\Request\Ping\Ping;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class PingTestCase extends ApiStubTestCase
{
    /**
     * @var Ping
     */
    protected $ping;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->ping = new Ping($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpointAndOptions()
    {
        $this->createEndpointTest($this->ping, 'ping');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        $this->createSendResponse($this->ping, '{
               "status": "ok",
               "meta": [
               ],
               "message": "Hi there! API version 3 here!"
           }', 'Hi there! API version 3 here!');
    }
}
