<?php
namespace SmartEmailing\v3\Tests;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Response as InternalResponse;

class BaseTestCase extends TestCase
{
    protected $username;
    protected $apiKey;

    /**
     * Constructs a test case with the given name. Setups default api-key/username
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // Load the Env variables
        $dotEnv = new Dotenv(__DIR__.'/../');
        $dotEnv->load();

        // Setup the username/api-key
        $this->username = $this->env('USERNAME', 'username');
        $this->apiKey = $this->env('API_KEY', 'password');
    }

    /**
     * Creates a tests for send request that will check if correct parameters are send to clients request method
     *
     * @param Api|\PHPUnit_Framework_MockObject_MockObject $apiStub
     * @param AbstractRequest                              $request
     * @param string                                       $endpointName
     * @param string                                       $httpMethod
     * @param array|null|object                                   $options
     */
    protected function createEndpointTest($apiStub, $request, $endpointName, $httpMethod = 'GET',
                                          $options = [])
    {
        // Build the client that will mock the client->request method
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);

        // Make a response that is valid and ok - prevent exception
        $response->expects($this->once())->method('getBody')->willReturn('{
               "status": "ok",
               "meta": [
               ],
               "message": "Hi there! API version 3 here!"
           }');

        // Build customizable options to check
        $optionsCheck = null;

        if (is_null($options)) {
            $optionsCheck = $this->anything();
        } else if (is_object($options)) {
            $optionsCheck = $options;
        } else {
            $optionsCheck = $this->equalTo($options);
        }

        // The send method will trigger the request once with given properties (request methods)
        $client->expects($this->once())->method('request')->with(
            $this->equalTo($httpMethod), $this->equalTo($endpointName), $optionsCheck

        )->willReturn($response);

        $apiStub->method('client')->willReturn($client);
        $request->send();
    }

    /**
     * Creates a response mock and runs the send method. Then checks for the response result.
     *
     * @param Api|\PHPUnit_Framework_MockObject_MockObject $apiStub
     * @param AbstractRequest                              $request
     * @param string                                       $responseText
     * @param string                                       $responseMessage
     * @param string                                       $responseStatus
     * @param array                                        $meta
     * @param string                                       $responseClass
     * @param int                                          $responseCode
     *
     * @return InternalResponse
     */
    public function createSendResponse($apiStub, $request, $responseText, $responseMessage,
                                       $responseStatus = InternalResponse::SUCCESS, array $meta = [],
                                       $responseClass = InternalResponse::class, $responseCode = 200)
    {

        $this->createMockHandlerToApi($apiStub, $responseText, $responseCode);

        // Run the request
        $response = $request->send();

        $this->assertResponse($response, $responseClass, $responseStatus, $responseMessage, $meta);
        return $response;
    }

    /**
     * Creates a response mock and runs the send method. Then checks for the response result.
     *
     * @param Api|\PHPUnit_Framework_MockObject_MockObject $apiStub
     * @param AbstractRequest                              $request
     * @param string                                       $responseText
     * @param string                                       $responseMessage
     * @param string                                       $responseStatus
     * @param array                                        $meta
     * @param string                                       $responseClass
     * @param int                                          $responseCode
     *
     * @return RequestException
     */
    public function createSendErrorResponse($apiStub, $request, $responseText, $responseMessage,
                                            $responseStatus = InternalResponse::SUCCESS, array $meta = [],
                                            $responseClass = InternalResponse::class, $responseCode = 200)
    {

        $this->createMockHandlerToApi($apiStub, $responseText, $responseCode);

        try {
            // Run the request
            $response = $request->send();

            $this->fail('The send request should raise an exception when Guzzle raises RequestException 
            (non 200 status code) or API returns 200 status code with error status in json');
        } catch (RequestException $exception) {
            $this->assertResponse($exception->response(), $responseClass, $responseStatus, $responseMessage, $meta);
            return $exception;
        }
    }

    /**
     * Creates a MockHandler with a response and mocks the client in mocked api
     *
     * @param Api|\PHPUnit_Framework_MockObject_MockObject $apiStub
     * @param string                                       $responseText
     * @param int                                          $responseCode
     */
    protected function createMockHandlerToApi($apiStub, $responseText, $responseCode)
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
        $apiStub->method('client')->willReturn($client);
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

    /**
     * Creates new API
     *
     * @param string|null $apiUrl
     *
     * @return Api
     */
    protected function createApi($apiUrl = null)
    {
        return new Api($this->username, $this->apiKey, $apiUrl);
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        return $value;
    }
}