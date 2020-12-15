<?php
namespace SmartEmailing\v3\Tests\Request;

use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;
use SmartEmailing\v3\Tests\Mock\RequestMock;

class AbstractRequestTestCase extends ApiStubTestCase
{
    /**
     * @var AbstractRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * Builds the ping instance on every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new RequestMock($this->apiStub);
    }

    /**
     * Test the arguments that are passed to request
     */
    public function testEndpointAndOptions()
    {
        $this->createEndpointTest($this->request, 'endpoint', 'GET', ['test']);
    }

    public function testResponse()
    {
        $this->createSendResponse($this->request, '{
               "status": "ok",
               "meta": [
               ],
               "message": "Test"
           }', 'Test');
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCodeWithResponseError()
    {
        $exception = $this->createSendErrorResponse($this->request, '{
               "status": "error",
               "meta": [
               ],
               "message": "An error"
           }', 'An error', Response::ERROR, [], Response::class, 500);

        $this->assertEquals('Client error: An error', $exception->getMessage(),
            'The exception should use json message if Guzzle returns simple error');
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull($exception->request(), 'Non 200 status code should have request - passed from guzzle exception');
    }

    /**
     * Tests the fatal error - status code 200 with custom message in json
     */
    public function testResponse200StatusCodeWithResponseError()
    {
        $exception = $this->createSendErrorResponse($this->request, '{
               "status": "error",
               "meta": [
               ],
               "message": "An error"
           }', 'An error', Response::ERROR, [], Response::class, 200);

        $this->assertEquals('Client error: An error', $exception->getMessage(), 'The exception should use json message');
        $this->assertEquals(200, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(200, $exception->response()->statusCode());
        $this->assertNull($exception->request(), 'Error with 200 status code has no request');
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCode()
    {
        $exception = $this->createSendErrorResponse(
            $this->request, null, null, Response::ERROR, [], Response::class, 500
        );

        $this->assertEquals('Client error', $exception->getMessage(), 'The exception should use the Guzzles message');
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull($exception->request(), 'Non 200 status code should have request - passed from guzzle exception');
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCodeWithJson()
    {
        $exception = $this->createSendErrorResponse(
            $this->request, '{
               "status": "error",
               "meta": [
               ],
               "message": "An error"
           }', 'An error', Response::ERROR, [], Response::class, 500
        );

        $this->assertEquals('Client error: An error', $exception->getMessage(), 'The guzzle message is simple, append message');
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull($exception->request(), 'Non 200 status code should have request - passed from guzzle exception');
    }

}
