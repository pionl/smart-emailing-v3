<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlists\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\Contactlists\Search\ContactlistSearchRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Search\ContactlistSearchResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    protected ContactlistSearchRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ContactlistSearchRequest($this->apiStub);
    }

    public function testDefaultEndpoint(): void
    {
        $this->expectClientRequest('contactlists', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            $this->assertArrayHasKey('query', $value);

            // The query parameters to send
            $query = $value['query'];
            $this->assertArrayHasKey('limit', $query);
            $this->assertArrayHasKey('offset', $query);
            $this->assertEquals(0, $query['offset'], 'The first page should have 0 offset');
            $this->assertEquals(100, $query['limit'], 'The default limit should be 100');
            $this->assertCount(2, $query, 'Default query should have only limit and offset');

            return true;
        }), $this->createDefaultResponse());
        $this->request->send();
    }

    public function testResponseEndpoint(): void
    {
        $this->expectClientRequest(null, null, null, $this->createDefaultResponse());

        $response = $this->request->send();

        $this->assertInstanceOf(ContactlistSearchResponse::class, $response);
        $this->assertTrue(is_array($response->data()));
        $this->assertCount(2, $response->data());

        /** @var Contactlist $contactlist */
        foreach ($response->data() as $contactlist) {
            $this->assertInstanceOf(Contactlist::class, $contactlist);
            $this->assertNotNull($contactlist->getName());
            $this->assertNotNull($contactlist->getId());
        }

        $this->assertEquals(1, $response->data()[0]->getId());
        $this->assertEquals('martin@smartemailing.cz', $response->data()[0]->getName());

        $this->assertEquals(8, $response->meta()->total_count);
    }

    public function testQuerySelect(): void
    {
        $this->createQueryValue('select', ['name'], 'select', 'select');
    }

    /**
     * Tests the setter method + query result
     *
     * @param mixed                $value
     * @param ContactlistSearchRequest|null $setAndGetObject object that will be used for setting/getting the value. Default is
     * Request
     */
    protected function createQueryValue(
        string $setMethod,
        $value,
        string $getProperty,
        string $queryKey,
        ?ContactlistSearchRequest $setAndGetObject = null
    ): void {
        if ($setAndGetObject === null) {
            $setAndGetObject = $this->request;
        }

        // Set the value
        $setAndGetObject->{$setMethod}($value);

        // Run the query
        $query = $this->request->query();

        // Check the value if it was set by the function
        if (isset($setAndGetObject->{$getProperty})) {
            $this->assertEquals($value, $setAndGetObject->{$getProperty});
        }

        $this->assertCount(3, $query, sprintf('The query should have 3 items: limit, offset, %s', $queryKey));
        $this->assertEquals(is_array($value) ? implode(',', $value) : $value, $query[$queryKey]);

        // Test override by public property
        $this->request->{$getProperty} = null;
        $this->assertNull($this->request->{$getProperty});
    }

    private function createDefaultResponse(): ResponseInterface
    {
        return $this->createClientResponse('{
            "status": "ok",
            "meta": {
                "total_count": 8,
                "displayed_count": 2,
                "offset": 0,
                "limit": 2
            },
            "data": [
                {
                    "replyto": "martin@smartemailing.cz",
                    "clickRate": null,
                    "hidden": 0,
                    "alertOut": 0,
                    "alertIn": 0,
                    "category": null,
                    "signature": null,
                    "sendername": "Martin Strouhal",
                    "segment_id": null,
                    "name": "martin@smartemailing.cz",
                    "openRate": null,
                    "senderemail": "martin@smartemailing.cz",
                    "id": 1,
                    "notes": null,
                    "trackedDefaultFields": "a:0:{}",
                    "publicname": "martin@smartemailing.cz",
                    "created": "2015-07-21 17:55:25"
                },
                {
                    "replyto": "martin@smartemailing.cz",
                    "clickRate": null,
                    "hidden": 0,
                    "alertOut": 0,
                    "alertIn": 0,
                    "category": null,
                    "signature": null,
                    "sendername": "Martin Strouhal",
                    "segment_id": null,
                    "name": "API TEST",
                    "openRate": null,
                    "senderemail": "martin@smartemailing.cz",
                    "id": 737,
                    "notes": null,
                    "trackedDefaultFields": "a:0:{}",
                    "publicname": null,
                    "created": "2015-09-10 11:31:25"
                }
            ]
        }');
    }
}
