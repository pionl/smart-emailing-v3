<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contacts\Search;

use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchRequest;
use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchResponse;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchResponse;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected ContactsSearchRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()
            ->contacts()
            ->searchRequest();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ContactsSearchRequest::class, $this->request);
    }

    public function testSend(): void
    {
        $this->assertEquals(1, $this->request->getPage());

        $this->request->filter()
            ->byBlacklisted(false);

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(
            ContactsSearchResponse::class,
            $response,
            'Search request must return own response'
        );
        $this->assertEquals(CustomFieldsSearchResponse::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());

        $data = $response->data();

        $this->assertTrue(is_array($data));

        foreach ($data as $contact) {
            $this->assertInstanceOf(Contact::class, $contact);
            $this->assertNotNull($contact->id);
            $this->assertNotNull($contact->emailAddress);
        }
    }
}
