<?php
namespace SmartEmailing\v3\Tests\Request\Ping;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Request\Ping\Ping;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\Response as InternalResponse;
use SmartEmailing\v3\Tests\BaseTestCase;

class PingTest extends BaseTestCase
{
    /**
     * @var Ping
     */
    protected $ping;

    /**
     * @var Api|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $apiStub;

    /**
     * Builds the ping instance on every test
     */
    public function setUp()
    {
        /** @var  $apiStub */
        $this->apiStub = $this->createMock(Api::class);
        $this->ping = new Ping($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpointAndOptions() {
        $this->createEndpointTest($this->apiStub, $this->ping, 'ping');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        $this->createSendResponse($this->apiStub, $this->ping, '{
               "status": "ok",
               "meta": [
               ],
               "message": "Hi there! API version 3 here!"
           }', 'Hi there! API version 3 here!');
    }
}
