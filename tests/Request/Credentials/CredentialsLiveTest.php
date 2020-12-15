<?php
namespace SmartEmailing\v3\Tests\Request\Credentials;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\Credentials\Response;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class CredentialsLiveTest extends BaseTestCase
{

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend()
    {
        if (!$this->canDoLiveTest) {
            // Always proof test - ignores the no test phpunit warning
            $this->assertTrue(true);
            return;
        }

        $response = $this->createApi()->credentials()->send();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::SUCCESS, $response->status());
        $this->assertEquals('Hi there! Your credentials are valid!', $response->message());
        $this->assertNotNull($response->accountId(), 'The account is missing from response.');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend401()
    {
        try {
            (new Api('test', 'password'))->credentials()->send();
            $this->fail('The response should raise an RequestException due incorrect credentials - Guzzle raises an exception');
        } catch (RequestException $exception) {
            /** @var Response $response */
            $response = $exception->response();
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(Response::ERROR, $response->status());
            $this->assertEquals('Authentication Failed', $response->message());
            $this->assertNull($response->accountId(), 'Default value of accountId should be null');
        }
    }
}
