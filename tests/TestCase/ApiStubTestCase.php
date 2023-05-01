<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractCollectionResponse;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Exceptions\RequestException;

abstract class ApiStubTestCase extends BaseTestCase
{
    /**
     * Default response that will be rewritten on every setUp
     *
     * @var StreamInterface
     */
    protected $defaultReturnResponse;

    /**
     * @var Api|MockObject
     */
    protected $apiStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiStub = $this->createMock(Api::class);

        $this->defaultReturnResponse = Utils::streamFor('{
               "status": "ok",
               "meta": [],
               "message": "Hi there! API version 3 here!"
           }');
    }

    /**
     * Creates a response mock and runs the send method. Then checks for the response result.
     *
     * @param AbstractRequest $request
     * @param string          $responseText
     * @param string|null     $responseMessage
     * @param string          $responseStatus
     * @param string          $responseClass
     * @param int             $responseCode
     *
     * @return AbstractResponse
     */
    public function createSendResponse(
        $request,
        $responseText,
        $responseMessage,
        $responseStatus = AbstractResponse::SUCCESS,
        array $meta = [],
        $responseClass = AbstractResponse::class,
        $responseCode = 200
    ) {
        $this->createMockHandlerToApi($responseText, $responseCode);

        // Run the request
        $response = $request->send();

        $this->assertResponse($response, $responseClass, $responseStatus, $responseMessage, $meta);
        return $response;
    }

    /**
     * Creates a response mock and runs the send method. Then checks for the response result.
     *
     * @param AbstractRequest $request
     * @param string|null     $responseText
     * @param string|null     $responseMessage
     * @param string          $responseStatus
     * @param string          $responseClass
     * @param int             $responseCode
     *
     * @return RequestException
     */
    public function createSendErrorResponse(
        $request,
        $responseText,
        $responseMessage,
        $responseStatus = AbstractResponse::SUCCESS,
        $meta = null,
        $responseClass = AbstractResponse::class,
        $responseCode = 200
    ) {
        $this->createMockHandlerToApi($responseText, $responseCode);

        try {
            // Run the request
            $request->send();

            $this->fail('The send request should raise an exception when Guzzle raises RequestException ' .
              '(non 200 status code) or API returns 200 status code with error status in json');
        } catch (RequestException $requestException) {
            $this->assertResponse(
                $requestException->response(),
                $responseClass,
                $responseStatus,
                $responseMessage,
                $meta
            );
            return $requestException;
        }
    }

    /**
     * Creates a tests for send request that will check if correct parameters are send to clients request method
     *
     * @param AbstractRequest   $request
     * @param string|null|mixed $endpointName
     * @param string|null|mixed $httpMethod
     * @param string|null|mixed $options
     *
     * @return AbstractResponse
     */
    protected function createEndpointTest($request, $endpointName, $httpMethod = 'GET', $options = [])
    {
        $this->stubClientResponse($endpointName, $httpMethod, $options);
        return $request->send();
    }

    /**
     * Builds the response/client mocks and setups for a clients request call
     *
     * @param string|null|mixed $endpointName
     * @param string|null|mixed $httpMethod
     * @param array|callback|null        $options
     */
    protected function stubClientResponse($endpointName, $httpMethod = 'GET', $options = [])
    {
        // Build the client that will mock the client->request method
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);

        // Make a response that is valid and ok - prevent exception
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->defaultReturnResponse);

        $response->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(200);

        // The send method will trigger the request once with given properties (request methods)
        $client->expects($this->once())
            ->method('request')
            ->with(
                $this->valueConstraint($httpMethod),
                $this->valueConstraint($endpointName),
                $this->valueConstraint($options)
            )->willReturn($response);

        $this->apiStub->method('client')
            ->willReturn($client);
    }

    /**
     * Builds the correct constraint value based on input value
     *
     * @param string|null|mixed $desiredValue
     */
    protected function valueConstraint($desiredValue)
    {
        if ($desiredValue === null) {
            return $this->anything();
        } elseif (is_object($desiredValue)) {
            return $desiredValue;
        }

        return $this->equalTo($desiredValue);
    }

    /**
     * Creates a MockHandler with a response and mocks the client in mocked api
     *
     * @param string $responseText
     * @param int    $responseCode
     *
     * @return Client
     */
    protected function createMockHandlerToApi($responseText, $responseCode)
    {
        $responseQueue = [];
        if ($responseCode > 300) {
            $responseQueue[] = new BadResponseException(
                'Client error',
                new Request('GET', 'test'),
                new Response($responseCode, [], $responseText)
            );
        } else {
            $responseQueue[] = new Response($responseCode, [], $responseText);
        }

        // Return own responses
        $handler = new MockHandler($responseQueue);

        // Build the client
        $client = new Client([
            'handler' => $handler,
        ]);

        // Replace the client
        $this->apiStub->method('client')
            ->willReturn($client);
        return $client;
    }

    /**
     * @param AbstractResponse $response
     * @param string           $responseClass
     * @param string           $responseStatus
     * @param string           $responseMessage
     */
    protected function assertResponse($response, $responseClass, $responseStatus, $responseMessage, $meta = [])
    {
        // Check the response
        $this->assertInstanceOf($responseClass, $response);
        $this->assertEquals($responseStatus, $response->status());
        $this->assertEquals($responseMessage, $response->message());
        if ($response instanceof AbstractCollectionResponse) {
            $this->assertEquals($meta, $response->meta());
        }
    }
}
