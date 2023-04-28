<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Import;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Import\Settings;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class ImportTestCase extends ApiStubTestCase
{
    /**
     * @var Import
     */
    protected $import;

    protected function setUp(): void
    {
        parent::setUp();

        $this->import = new Import($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint()
    {
        $this->createEndpointTest($this->import, 'import', 'POST', $this->arrayHasKey('json'));
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(Settings::class, $this->import->settings());
        $this->assertCount(0, $this->import->contacts());
    }

    public function testAddContact()
    {
        $this->assertCount(1, $this->import->addContact(new Contact('test@test.cz'))->contacts());
        $this->assertCount(2, $this->import->addContact(new Contact('test2@test.cz'))->contacts());
        $this->assertCount(3, $this->import->addContact(new Contact('test@test.cz'))->contacts());
    }

    public function testNewContact()
    {
        $this->import->newContact('test@test.cz');
        $this->assertCount(1, $this->import->contacts());
    }

    public function testChunkMode()
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addContact(new Contact(sprintf('test+%d@test.cz', $i)));
        }

        // Build the client that will mock the client->request method
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);

        // The array will be chunked in 3 groups
        $willBeCalled = $this->exactly(3);

        // Make a response that is valid and ok - prevent exception
        $response->expects($this->atLeastOnce())
            ->method('getBody')
            ->willReturn($this->defaultReturnResponse);
        $called = 0;
        $client->expects($willBeCalled)
            ->method('request')
            ->with(
                $this->valueConstraint('POST'),
                $this->valueConstraint('import'),
                $this->callback(function ($value) use (&$called): bool {
                    $this->assertTrue(is_array($value), 'Options should be array');
                    $this->assertArrayHasKey('json', $value, 'Options should contain json');
                    $this->assertArrayHasKey('data', $value['json'], 'JSON must have data array');
                    $this->assertArrayHasKey('settings', $value['json'], 'JSON must have settings');
                    ++$called;

                    switch ($called) {
                        case 1:
                            $this->assertCount(500, $value['json']['data']);
                            $this->assertEquals('test+1@test.cz', $value['json']['data'][0]->emailAddress);
                            break;
                        case 2:
                            $this->assertCount(500, $value['json']['data']);
                            $this->assertEquals('test+501@test.cz', $value['json']['data'][0]->emailAddress);
                            break;
                        case 3: // Last pack of contacts is smaller
                            $this->assertCount(250, $value['json']['data']);
                            $this->assertEquals('test+1001@test.cz', $value['json']['data'][0]->emailAddress);
                            break;
                    }

                    return true;
                })
            )->willReturn($response);

        $this->apiStub->method('client')
            ->willReturn($client);
        $this->import->send();
    }

    public function testChunkModeError()
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addContact(new Contact(sprintf('test+%d@test.cz', $i)));
        }

        // Build the client that will mock the client->request method
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);

        // Make a response that is valid and ok - prevent exception
        $response->expects($this->atLeastOnce())
            ->method('getBody')
            ->willReturn(Utils::streamFor('{
            "status": "error",
            "meta": [],
            "message": "Emailaddress invalid@email@gmail.com is not valid email address."
        }'));
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(422);

        $client->expects($this->once())
            ->method('request')
            ->with(
                $this->valueConstraint('POST'),
                $this->valueConstraint('import'),
                $this->callback(function ($value): bool {
                    $this->assertTrue(is_array($value), 'Options should be array');
                    $this->assertArrayHasKey('json', $value, 'Options should contain json');
                    $this->assertArrayHasKey('data', $value['json'], 'JSON must have data array');
                    $this->assertArrayHasKey('settings', $value['json'], 'JSON must have settings');
                    $this->assertCount(500, $value['json']['data']);
                    $this->assertEquals('test+1@test.cz', $value['json']['data'][0]->emailAddress);

                    return true;
                })
            )->willReturn($response);

        $this->apiStub->method('client')
            ->willReturn($client);
        $this->expectException(RequestException::class);
        $this->import->send();
    }
}
