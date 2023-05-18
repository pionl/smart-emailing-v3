<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Import;

use SmartEmailing\v3\Endpoints\Import\Contacts\ImportContactsRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\ImportContactsSettings;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class ImportTestCase extends ApiStubTestCase
{
    protected ImportContactsRequest $import;

    protected function setUp(): void
    {
        parent::setUp();

        $this->import = new ImportContactsRequest($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint(): void
    {
        $this->expectClientRequest('import', 'POST', $this->arrayHasKey('json'));
        $this->import->send();
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(ImportContactsSettings::class, $this->import->settings());
        $this->assertCount(0, $this->import->contacts());
    }

    public function testAddContact(): void
    {
        $this->assertCount(1, $this->import->addContact(new Contact('test@test.cz'))->contacts());
        $this->assertCount(2, $this->import->addContact(new Contact('test2@test.cz'))->contacts());
        $this->assertCount(3, $this->import->addContact(new Contact('test@test.cz'))->contacts());
    }

    public function testNewContact(): void
    {
        $this->import->newContact('test@test.cz');
        $this->assertCount(1, $this->import->contacts());
    }

    public function testChunkMode(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addContact(new Contact(sprintf('test+%d@test.cz', $i)));
        }

        $response = $this->createClientResponse();

        $this->expectClientRequest('import', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+1@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('import', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+501@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('import', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(250, $data);
            $this->assertEquals('test+1001@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->import->send();
    }

    public function testChunkModeError(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addContact(new Contact(sprintf('test+%d@test.cz', $i)));
        }

        $response = $this->createClientErrorResponse(
            'Emailaddress invalid@email@gmail.com is not valid email address.'
        );

        $this->expectClientRequest('import', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+1@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectException(RequestException::class);
        $this->import->send();
    }
}
