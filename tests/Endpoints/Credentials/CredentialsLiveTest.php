<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Credentials;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\Credentials\CredentialsResponse;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class CredentialsLiveTest extends LiveTestCase
{
    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend(): void
    {
        $response = $this->createApi()
            ->credentials();

        $this->assertInstanceOf(CredentialsResponse::class, $response);
        $this->assertEquals(CredentialsResponse::SUCCESS, $response->status());
        $this->assertEquals('Hi there! Your credentials are valid!', $response->message());
        $this->assertNotNull($response->accountId(), 'The account is missing from response.');
    }

    /**
     * Mocks the request and checks if request is returned via send method
     */
    public function testSend401(): void
    {
        try {
            (new Api('test', 'password'))->credentials();
            $this->fail(
                'The response should raise an RequestException due incorrect credentials - Guzzle raises an exception'
            );
        } catch (RequestException $requestException) {
            /** @var CredentialsResponse $response */
            $response = $requestException->response();
            $this->assertInstanceOf(CredentialsResponse::class, $response);
            $this->assertEquals(CredentialsResponse::ERROR, $response->status());
            $this->assertEquals('Authentication Failed', $response->message());
            $this->expectException(PropertyRequiredException::class);
            $response->accountId();
        }
    }
}
