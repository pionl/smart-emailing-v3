<?php
namespace SmartEmailing\v3\Tests\TestCase;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\Response as InternalResponse;

abstract class ApiStubTestCase extends BaseTestCase
{
    /**
     * Default response that will be rewritten on every setUp
     * @var string
     */
    protected $defaultReturnResponse;

    /**
     * @var Api|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $apiStub;

    protected function setUp()
    {
        parent::setUp();

        /** @var  $apiStub */
        $this->apiStub = $this->createMock(Api::class);

        $this->defaultReturnResponse = '{
               "status": "ok",
               "meta": [
               ],
               "message": "Hi there! API version 3 here!"
           }';
    }


    /**
     * Creates a tests for send request that will check if correct parameters are send to clients request method
     *
     * @param AbstractRequest                                 $request
     * @param string|null|mixed $endpointName
     * @param string|null|mixed $httpMethod
     * @param string|null|mixed $options
     *
     * @return \SmartEmailing\v3\Request\Response
     */
    protected function createEndpointTest($request, $endpointName, $httpMethod = 'GET',
                                          $options = [])
    {
        $this->stubClientResponse($endpointName, $httpMethod, $options);
        return $request->send();
    }

    /**
     * Builds the response/client mocks and setups for a clients request call
     *
     * @param string|null|mixed $endpointName
     * @param string|null|mixed $httpMethod
     * @param array                                           $options
     */
    protected function stubClientResponse($endpointName, $httpMethod = 'GET', $options = [])
    {
        // Build the client that will mock the client->request method
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);

        // Make a response that is valid and ok - prevent exception
        $response->expects($this->once())->method('getBody')->willReturn($this->defaultReturnResponse);


        // The send method will trigger the request once with given properties (request methods)
        $client->expects($this->once())->method('request')->with(
            $this->valueConstraint($httpMethod),
            $this->valueConstraint($endpointName),
            $this->valueConstraint($options)

        )->willReturn($response);

        $this->apiStub->method('client')->willReturn($client);
    }

    /**
     * Builds the correct constraint value based on input value
     *
     * @param string|null|mixed $desiredValue
     */
    protected function valueConstraint($desiredValue)
    {
        if (is_null($desiredValue)) {
            return $this->anything();
        } else if (is_object($desiredValue)) {
            return $desiredValue;
        }

        return $this->equalTo($desiredValue);
    }


    /**
     * Creates a response mock and runs the send method. Then checks for the response result.
     *
     * @param AbstractRequest $request
     * @param string          $responseText
     * @param string          $responseMessage
     * @param string          $responseStatus
     * @param array           $meta
     * @param string          $responseClass
     * @param int             $responseCode
     *
     * @return InternalResponse
     */
    public function createSendResponse($request, $responseText, $responseMessage,
                                       $responseStatus = InternalResponse::SUCCESS, array $meta = [],
                                       $responseClass = InternalResponse::class, $responseCode = 200)
    {

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
     * @param string          $responseText
     * @param string          $responseMessage
     * @param string          $responseStatus
     * @param array           $meta
     * @param string          $responseClass
     * @param int             $responseCode
     *
     * @return RequestException
     */
    public function createSendErrorResponse($request, $responseText, $responseMessage,
                                            $responseStatus = InternalResponse::SUCCESS, array $meta = [],
                                            $responseClass = InternalResponse::class, $responseCode = 200)
    {

        $this->createMockHandlerToApi($responseText, $responseCode);

        try {
            // Run the request
            $request->send();

            $this->fail('The send request should raise an exception when Guzzle raises RequestException
            (non 200 status code) or API returns 200 status code with error status in json');
            return null;
        } catch (RequestException $exception) {
            $this->assertResponse($exception->response(), $responseClass, $responseStatus, $responseMessage, $meta);
            return $exception;
        }
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
                'Client error', new Request('GET', 'test'), new Response($responseCode, [], $responseText)
            );
        } else {
            $responseQueue[] = new Response($responseCode, [], $responseText);
        }

        // Return own responses
        $handler = new MockHandler($responseQueue);

        // Build the client
        $client = new Client(['handler' => $handler]);

        // Replace the client
        $this->apiStub->method('client')->willReturn($client);
        return $client;
    }

    /**
     * @param InternalResponse $response
     * @param string           $responseClass
     * @param string           $responseStatus
     * @param string           $responseMessage
     * @param array            $meta
     */
    protected function assertResponse($response, $responseClass, $responseStatus, $responseMessage, array $meta)
    {
        // Check the response
        $this->assertInstanceOf($responseClass, $response);
        $this->assertEquals($responseStatus, $response->status());
        $this->assertEquals($responseMessage, $response->message());
        $this->assertEquals($meta, $response->meta());
    }

}
