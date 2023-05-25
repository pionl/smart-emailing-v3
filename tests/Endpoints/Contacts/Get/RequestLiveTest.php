<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contacts\Get;

use SmartEmailing\v3\Endpoints\Contacts\Get\ContactsGetRequest;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected ContactsGetRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ContactsGetRequest($this->createApi(), 1);
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ContactsGetRequest::class, $this->request);
    }

    public function testSend(): void
    {
        // Change this if you want to try live
        $fieldId = 1;
        $this->request = new ContactsGetRequest($this->createApi(), $fieldId);

        // Comment if you want to send request
        $this->markTestSkipped();

        // Your
        $contact = $this->request->send();

        $this->assertTrue(is_object($contact), 'Not found');
    }
}
