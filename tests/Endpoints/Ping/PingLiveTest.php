<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Ping;

use SmartEmailing\v3\Endpoints\Ping\PingRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class PingLiveTest extends LiveTestCase
{
    protected PingRequest $ping;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->ping = new PingRequest($this->createApi());
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend(): void
    {
        $response = $this->createApi()
            ->ping();
        $this->assertInstanceOf(StatusResponse::class, $response);
        $this->assertEquals(StatusResponse::SUCCESS, $response->status());
        $this->assertEquals('Hi there! API version 3 here!', $response->message());
    }
}
