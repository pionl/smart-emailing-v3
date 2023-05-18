<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
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
     */
    protected StreamInterface $defaultReturnResponse;

    /**
     * @var Api|MockObject
     */
    protected $apiStub;

    private Client $client;

    private MockHandler $clientHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiStub = $this->createMock(Api::class);
        $this->clientHandler = new MockHandler();

        // Build the client
        $this->client = new Client([
            'handler' => $this->clientHandler,
        ]);

        $this->apiStub->method('client')
            ->willReturn($this->client);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->assertEquals(0, $this->clientHandler->count(), 'Not all expected requests were called.');
    }

    protected function assertThrowsRequestException(AbstractRequest $request): RequestException
    {
        try {
            // Run the request
            $request->send();
            $this->fail('The send request should raise an exception when Guzzle raises RequestException ' .
              '(non 200 status code) or API returns 200 status code with error status in json');
        } catch (RequestException $requestException) {
            return $requestException;
        }
    }

    /**
     * @param array|callback|Constraint|null $expectedOptions
     */
    protected function expectClientRequest(
        ?string $endpointName,
        ?string $httpMethod = 'GET',
        $expectedOptions = null,
        ResponseInterface $clientResponse = null
    ): void {
        $this->clientHandler->append(function (RequestInterface $request, $actualOptions) use (
            $endpointName,
            $httpMethod,
            $expectedOptions,
            $clientResponse
        ): ResponseInterface {
            $this->assertThat($request->getMethod(), $this->valueConstraint($httpMethod));
            $this->assertThat($request->getUri()->getPath(), $this->valueConstraint($endpointName));
            $actualOptions = [];
            $queryParams = [];
            parse_str($request->getUri()->getQuery(), $queryParams);
            if ($queryParams !== []) {
                $actualOptions['query'] = $queryParams;
            }
            $body = $request->getBody()
                ->getContents();
            if ($body !== '' && $body !== '0') {
                $actualOptions['json'] = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            }
            $this->assertThat($actualOptions, $this->valueConstraint($expectedOptions), print_r($actualOptions, true));
            return $clientResponse ?? $this->createClientResponse();
        });
    }

    protected function createClientResponse(?string $returnResponse = null, int $statusCode = 200): ResponseInterface
    {
        $defaultResponse = '{
           "status": "ok",
           "meta": [],
           "message": "Hi there! API version 3 here!"
        }';
        return new Response($statusCode, [], $returnResponse ?? $defaultResponse);
    }

    protected function createClientErrorResponse(?string $message = null, int $statusCode = 422): ResponseInterface
    {
        $returnResponse = json_encode([
            'status' => 'error',
            'meta' => [],
            'message' => $message,
        ], JSON_THROW_ON_ERROR);
        return $this->createClientResponse($returnResponse ?: '', $statusCode);
    }

    /**
     * Builds the correct constraint value based on input value
     *
     * @param string|null|array|callback|Constraint $desiredValue
     */
    protected function valueConstraint($desiredValue): Constraint
    {
        if ($desiredValue === null) {
            return $this->anything();
        } elseif ($desiredValue instanceof Constraint) {
            return $desiredValue;
        }

        return $this->equalTo($desiredValue);
    }

    /**
     * Creates a MockHandler with a response and mocks the client in mocked api
     */
    protected function expectClientResponse(?string $responseText, int $responseCode = 200): void
    {
        if ($responseCode > 300) {
            $clientResponse = new BadResponseException(
                'Client error',
                new Request('GET', 'test'),
                new Response($responseCode, [], $responseText)
            );
        } else {
            $clientResponse = new Response($responseCode, [], $responseText);
        }

        $this->clientHandler->append($clientResponse);
    }

    /**
     * @param class-string $responseClass
     */
    protected function assertResponse(
        AbstractResponse $response,
        ?string $responseMessage = null,
        string $responseStatus = AbstractResponse::SUCCESS,
        string $responseClass = AbstractResponse::class,
        ?array $meta = []
    ): void {
        // Check the response
        $this->assertInstanceOf($responseClass, $response);
        $this->assertEquals($responseStatus, $response->status());
        $this->assertEquals($responseMessage, $response->message());
        if ($response instanceof AbstractCollectionResponse) {
            $this->assertEquals($meta, $response->meta());
        }
    }

    protected function assertHasJsonData(array $value, string $key): array
    {
        $this->assertArrayHasKey('json', $value, 'Should contain json data');
        $this->assertArrayHasKey($key, $value['json'], sprintf("JSON must have '%s' array", $key));
        $this->assertIsArray($value['json'][$key], sprintf("JSON '%s' must be an array", $key));
        return $value['json'][$key];
    }
}
