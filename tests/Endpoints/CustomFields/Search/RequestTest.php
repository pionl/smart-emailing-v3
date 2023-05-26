<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchFilters;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchResponse;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    protected CustomFieldsSearchRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CustomFieldsSearchRequest($this->apiStub);
    }

    //region Test endpoint
    public function testDefaultEndpoint(): void
    {
        $this->expectClientRequest('customfields', 'GET', $this->callback(function ($value): bool {
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

    public function testFilteredEndpoint(): void
    {
        $this->request->setPage(2, 50)
            ->select(['name', 'type']);

        // Apply filters
        $this->request->filter()
            ->byType(CustomFieldDefinition::CHECKBOX);

        $this->expectClientRequest('customfields', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            $this->assertArrayHasKey('query', $value);

            // The query parameters to send
            $query = $value['query'];
            $this->assertArrayHasKey('limit', $query);
            $this->assertArrayHasKey('offset', $query);
            $this->assertEquals(50, $query['offset'], 'The second page should the limit value');
            $this->assertEquals(50, $query['limit'], 'The default limit should be 100');
            $this->assertEquals(CustomFieldDefinition::CHECKBOX, $query['type'], 'There should by type filter');
            $this->assertEquals('name,type', $query['select']);
            $this->assertCount(4, $query, 'Default query should have only limit and offset');

            return true;
        }), $this->createDefaultResponse());

        $this->request->send();
    }

    public function testResponseEndpoint(): void
    {
        $this->expectClientRequest(null, null, null, $this->createDefaultResponse());

        $response = $this->request->send();

        $this->assertInstanceOf(CustomFieldsSearchResponse::class, $response);
        $this->assertTrue(is_array($response->data()));
        $this->assertCount(2, $response->data());

        /** @var CustomFieldDefinition $customField */
        foreach ($response->data() as $customField) {
            $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
            $this->assertNotNull($customField->name);
            $this->assertNotNull($customField->type);
            $this->assertNotNull($customField->id);
        }

        $this->assertEquals('select', $response->data()[0]->type);
        $this->assertEquals(1, $response->data()[0]->id);
        $this->assertEquals('my select', $response->data()[0]->name);

        $this->assertEquals(8, $response->meta()->total_count);
    }

    public function testExpandedResponseEndpoint(): void
    {
        $this->expectClientRequest(null, null, null, $this->createExpandedDefaultResponse());

        $response = $this->request->expandCustomFieldOptions()
            ->send();

        $this->assertEquals('Tokyo', $response->data()[0]->options()->get(0)->name);
        $this->assertEquals('Torino', $response->data()[0]->options()->getById(3)->name);
    }

    //endregion

    //region Test query
    public function testQuerySort(): void
    {
        $this->createQueryValue('sortBy', ['type'], 'sort', 'sort');
    }

    public function testQueryExpand(): void
    {
        $this->createQueryValue('expandBy', ['customfield_options'], 'expand', 'expand');
    }

    public function testQueryExpandFail(): void
    {
        try {
            $this->createQueryValue('expandBy', ['test'], 'expand', 'expand');
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertEquals('These values are not allowed: test', $invalidFormatException->getMessage());
        }
    }

    public function testQuerySelect(): void
    {
        $this->createQueryValue('select', ['name'], 'select', 'select');
    }

    public function testQueryFilterById(): void
    {
        $this->createQueryValue('byId', '10', 'id', 'id', $this->request->filter());
    }

    public function testQueryFilterByName(): void
    {
        $this->createQueryValue('byName', 'test', 'name', 'name', $this->request->filter());
    }

    public function testQueryFilterByType(): void
    {
        $this->createQueryValue('byType', CustomFieldDefinition::CHECKBOX, 'type', 'type', $this->request->filter());
    }

    public function testQueryFilterByTypeFail(): void
    {
        try {
            $this->createQueryValue('byType', 'test', 'type', 'type', $this->request->filter());
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertStringContainsString("Value 'test' not allowed", $invalidFormatException->getMessage());
        }
    }

    /**
     * Tests the setter method + query result
     *
     * @param mixed                $value
     * @param CustomFieldsSearchFilters|CustomFieldsSearchRequest|null $setAndGetObject object that will be used for setting/getting the value. Default is
     * Request
     */
    protected function createQueryValue(
        string $setMethod,
        $value,
        string $getProperty,
        string $queryKey,
        $setAndGetObject = null
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
                    "id": 1,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=1",
                    "name": "my select",
                    "type": "select"
                },
                {
                    "id": 2,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=2",
                    "name": "my checkbox",
                    "type": "checkbox"
                }
            ]
        }');
    }

    private function createExpandedDefaultResponse(): ResponseInterface
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
                    "id": 1,
                    "customfield_options": [
                        {
                            "customfield_id": 1,
                            "id": 1,
                            "order": 0,
                            "name": "Tokyo"
                        },
                        {
                            "customfield_id": 1,
                            "id": 2,
                            "order": 1,
                            "name": "Sydney"
                        },
                        {
                            "customfield_id": 1,
                            "id": 3,
                            "order": 2,
                            "name": "Torino"
                        }
                    ],
                    "name": "my select",
                    "type": "select"
                },
                {
                    "id": 2,
                    "customfield_options": [
                        {
                            "customfield_id": 2,
                            "id": 1,
                            "order": 0,
                            "name": "Tokyo"
                        },
                        {
                            "customfield_id": 2,
                            "id": 2,
                            "order": 1,
                            "name": "Sydney"
                        },
                        {
                            "customfield_id": 2,
                            "id": 3,
                            "order": 2,
                            "name": "Torino"
                        }
                    ],
                    "name": "my checkbox",
                    "type": "checkbox"
                }
            ]
        }');
    }

    //endregion
}
