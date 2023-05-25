<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Tests\Mock\RequestMock;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class AbstractRequestTestCase extends ApiStubTestCase
{
    /**
     * @var AbstractRequest|RequestMock
     */
    protected $request;

    /**
     * Builds the ping instance on every test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new RequestMock($this->apiStub);
    }

    /**
     * Test the arguments that are passed to request
     */
    public function testEndpointAndOptions(): void
    {
        $this->expectClientRequest('endpoint', 'GET', [
            'json' => ['test'],
        ]);
        $this->request->send();
    }

    public function testResponse(): void
    {
        $this->expectClientResponse('{
            "status": "ok",
            "meta": [],
            "message": "Test"
        }');
        $response = $this->request->send();
        $this->assertResponse($response, 'Test');
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCodeWithResponseError(): void
    {
        $this->expectClientResponse('{
           "status": "error",
           "meta": [
           ],
           "message": "An error"
        }', 500);

        $exception = $this->assertThrowsRequestException($this->request);

        $this->assertResponse($exception->response(), 'An error', AbstractResponse::ERROR);

        $this->assertEquals(
            'Client error: An error',
            $exception->getMessage(),
            'The exception should use json message if Guzzle returns simple error'
        );
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull(
            $exception->request(),
            'Non 200 status code should have request - passed from guzzle exception'
        );
    }

    /**
     * Tests the fatal error - status code 200 with custom message in json
     */
    public function testResponse200StatusCodeWithResponseError(): void
    {
        $this->expectClientResponse('{
            "status": "error",
            "meta": [],
            "message": "An error"
        }');

        $exception = $this->assertThrowsRequestException($this->request);

        $this->assertResponse($exception->response(), 'An error', AbstractResponse::ERROR);

        $this->assertEquals(
            'Client error: An error',
            $exception->getMessage(),
            'The exception should use json message'
        );
        $this->assertEquals(200, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(200, $exception->response()->statusCode());
        $this->assertNull($exception->request(), 'Error with 200 status code has no request');
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCode(): void
    {
        $this->expectClientResponse(null, 500);

        $exception = $this->assertThrowsRequestException($this->request);

        $this->assertResponse($exception->response(), 'JSON: Syntax error', AbstractResponse::ERROR);

        $this->assertEquals(
            'Client error: JSON: Syntax error',
            $exception->getMessage(),
            'The exception should use the Guzzles message'
        );
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull(
            $exception->request(),
            'Non 200 status code should have request - passed from guzzle exception'
        );
    }

    /**
     * Tests the fatal error - status code 500 with custom message in json
     */
    public function testResponseStatusCodeWithJson(): void
    {
        $this->expectClientResponse('{
           "status": "error",
           "meta": [
           ],
           "message": "An error"
        }', 500);

        $exception = $this->assertThrowsRequestException($this->request);

        $this->assertResponse($exception->response(), 'An error', AbstractResponse::ERROR);

        $this->assertEquals(
            'Client error: An error',
            $exception->getMessage(),
            'The guzzle message is simple, append message'
        );
        $this->assertEquals(500, $exception->getCode(), 'Exception must have same code as status code');
        $this->assertEquals(500, $exception->response()->statusCode());
        $this->assertNotNull(
            $exception->request(),
            'Non 200 status code should have request - passed from guzzle exception'
        );
    }
}
