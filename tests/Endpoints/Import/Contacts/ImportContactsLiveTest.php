<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Import\Contacts;

use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Endpoints\Import\Contacts\ImportContactsRequest;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class ImportContactsLiveTest extends LiveTestCase
{
    protected ImportContactsRequest $import;

    protected function setUp(): void
    {
        parent::setUp();

        $this->import = $this->createApi()
            ->importRequest();
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testBasic(): void
    {
        $this->assertInstanceOf(ImportContactsRequest::class, $this->import);
    }

    /**
     * Live test of sync
     */
    public function testContactImport(): void
    {
        // Comment if you want to try
        $this->markTestSkipped();

        $contactFull = new Contact('test2@test.cz');

        $contactFull->setGender('M')
            ->setNotes('test')
            ->setBirthday('today')
            ->setBlacklisted(true);

        $this->import->addContact(new Contact('test@test.cz'))
            ->addContact($contactFull);

        $this->import->settings()
            ->setSkipInvalidEmails(true);

        $response = $this->import->send();

        $this->assertEquals(AbstractResponse::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());
    }
}
