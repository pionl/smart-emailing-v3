<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Ping;

use SmartEmailing\v3\Request\Ping\Ping;
use SmartEmailing\v3\Request\Response;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class PingLiveTest extends BaseTestCase
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
        $this->ping = $this->createApi()
            ->ping();
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        if (! $this->canDoLiveTest) {
            // Always proof test - ignores the no test phpunit warning
            $this->assertInstanceOf(Ping::class, $this->ping);
            return;
        }

        $response = $this->ping->send();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::SUCCESS, $response->status());
        $this->assertEquals('Hi there! API version 3 here!', $response->message());
        $this->assertEquals([], $response->meta());
    }
}
