<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlists\Create;

use SmartEmailing\v3\Endpoints\Contactlists\Create\ContactlistCreateRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Create\ContactlistCreateResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected ContactlistCreateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()
            ->contactlist()
            ->createRequest();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ContactlistCreateRequest::class, $this->request);
    }

    public function testSend(): void
    {
        $contactList = new Contactlist();
        $contactList->setName('testing');

        $this->request->setContactList($contactList);
        $this->assertNotNull($this->request->contactList());

        // Comment if you want to send request
        $this->markTestSkipped();

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(
            ContactlistCreateResponse::class,
            $response,
            'Create request must return own response'
        );
        $this->assertEquals(ContactlistCreateResponse::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());

        // Check custom-field
        $contactList = $response->data();
        $this->assertInstanceOf(Contactlist::class, $contactList);
        $this->assertTrue(is_numeric($contactList->id));
        $this->assertEquals('testing', $contactList->name);
    }
}
