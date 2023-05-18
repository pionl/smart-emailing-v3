<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields;

use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateRequest;
use SmartEmailing\v3\Endpoints\CustomFields\CustomFieldsEndpoint;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchResponse;
use SmartEmailing\v3\Exceptions\JsonDataMissingException;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CustomFieldsTest extends ApiStubTestCase
{
    protected CustomFieldsEndpoint $fields;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fields = new CustomFieldsEndpoint($this->apiStub);
    }

    public function testCreateRequest(): void
    {
        $request = $this->fields->createRequest();

        $this->assertInstanceOf(CustomFieldsCreateRequest::class, $request);
        $this->assertNull($request->customField());
    }

    public function testCreateRequestCustomField(): void
    {
        $request = $this->fields->createRequest(new CustomFieldDefinition('test'));

        $this->assertInstanceOf(CustomFieldsCreateRequest::class, $request);
        $this->assertNotNull($request->customField());
    }

    public function testCreate(): void
    {
        $this->expectClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "id": 1,
                "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=1",
                "name": "test",
                "type": "checkbox"
            }
        }');
        $response = $this->fields->create(new CustomFieldDefinition('test'));
        $this->assertInstanceOf(CustomFieldDefinition::class, $response);
    }

    public function testSearchRequest(): void
    {
        $request = $this->fields->searchRequest(1, 10);

        $this->assertInstanceOf(CustomFieldsSearchRequest::class, $request);
        $this->assertEquals(1, $request->page);
        $this->assertEquals(10, $request->limit);
    }

    public function testSearchFailOnData(): void
    {
        // The exact endpoint test are in specific tests for the request
        // Checks if request is called in the send method
        $this->expectClientRequest(null, null, null);

        try {
            $response = $this->fields->searchRequest()
                ->send();
            $this->assertInstanceOf(CustomFieldsSearchResponse::class, $response);
        } catch (JsonDataMissingException $jsonDataMissingException) {
            $this->assertEquals("The JSON response is missing 'data' value", $jsonDataMissingException->getMessage());
        }
    }

    public function testGetByName(): void
    {
        $this->expectClientResponse('{
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
                    "name": "test",
                    "type": "checkbox"
                }
            ]
        }');

        $customField = $this->fields->getByName('test');

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(1, $customField->id);
    }
}
