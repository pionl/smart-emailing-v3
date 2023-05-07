<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Create;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateResponse;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTestCase extends ApiStubTestCase
{
    protected CustomFieldsCreateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CustomFieldsCreateRequest($this->apiStub);
        $this->defaultReturnResponse = Utils::streamFor('{
            "status": "created",
            "meta": [],
            "data": {
                "id": 10,
                "options": null,
                "name": "Fruit",
                "type": "text"
            }
        }');
    }

    public function testEndpointAndResponse(): void
    {
        $this->request->setCustomField(new CustomFieldDefinition('Fruit', CustomFieldDefinition::TEXT));

        /** @var CustomFieldsCreateResponse $response */
        $response = $this->createEndpointTest(
            $this->request,
            'customfields',
            'POST',
            $this->callback(function ($array): bool {
                $this->assertTrue(is_array($array), 'Options must return an array');
                $this->assertArrayHasKey('json', $array, 'Options must return an json value');
                $this->assertTrue(is_array($array['json']), 'Json must be an array');
                $this->assertEquals([
                    'name' => 'Fruit',
                    'type' => CustomFieldDefinition::TEXT,
                ], $array['json']);

                return true;
            })
        );

        $this->assertInstanceOf(
            CustomFieldsCreateResponse::class,
            $response,
            'Create request must return own response'
        );
        $customField = $response->data();
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(10, $customField->id);
        $this->assertNull($customField->options);
        $this->assertEquals('text', $customField->type);
        $this->assertEquals('Fruit', $customField->name);
    }

    public function testConstructCustomField(): void
    {
        $request = new CustomFieldsCreateRequest($this->apiStub, new CustomFieldDefinition('test'));
        $this->assertNotNull($request->customField());
        $this->assertEquals('test', $request->customField()->name, 'Custom field is not same as passed');
    }
}
