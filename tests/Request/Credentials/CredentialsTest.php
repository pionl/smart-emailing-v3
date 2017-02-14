<?php
namespace SmartEmailing\v3\Tests\Request\Credentials;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\Credentials\Credentials;
use SmartEmailing\v3\Request\Credentials\Response;
use SmartEmailing\v3\Tests\BaseTestCase;

class CredentialsTest extends BaseTestCase
{
    /**
     * @var Credentials
     */
    protected $credentials;

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
        $this->credentials = new Credentials($this->apiStub);
    }

    /**
     * Tests the endpoint and options in the Credentials class
     */
    public function testEndpointAndOptions()
    {
        $this->createEndpointTest($this->apiStub, $this->credentials, 'check-credentials');
    }



    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        /** @var Response $response */
        $response = $this->createSendResponse($this->apiStub, $this->credentials, '{
            "status": "ok",
            "meta": [
            ],
            "message": "Hi there! Your credentials are valid!",
            "account_id": 2
        }', 'Hi there! Your credentials are valid!');

        $this->assertEquals(2, $response->accountId());
    }
}