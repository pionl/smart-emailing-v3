<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlists\Search;

use SmartEmailing\v3\Endpoints\Contactlists\Search\ContactlistSearchRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Search\ContactlistSearchResponse;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected ContactlistSearchRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()
            ->contactlist()
            ->searchRequest();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ContactlistSearchRequest::class, $this->request);
    }

    public function testSend(): void
    {
        $this->assertEquals(1, $this->request->getPage());

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(
            ContactlistSearchResponse::class,
            $response,
            'Search request must return own response'
        );
        $this->assertEquals(CustomFieldsSearchResponse::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());

        $data = $response->data();

        $this->assertTrue(is_array($data));

        foreach ($data as $contactlist) {
            $this->assertInstanceOf(Contactlist::class, $contactlist);
            $this->assertNotNull($contactlist->id);
            $this->assertNotNull($contactlist->name);
        }
    }
}
