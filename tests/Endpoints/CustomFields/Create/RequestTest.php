<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Create;

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
    }

    public function testEndpointAndResponse(): void
    {
        $this->request->setCustomField(new CustomFieldDefinition('Fruit', CustomFieldDefinition::TEXT));

        $expectedResponse = $this->createClientResponse('{
            "status": "created",
            "meta": [],
            "data": {
                "id": 10,
                "options": null,
                "name": "Fruit",
                "type": "text"
            }
        }');

        $this->expectClientRequest(
            'customfields',
            'POST',
            $this->callback(function ($options): bool {
                $this->assertArrayHasKey('json', $options, 'Options must return an json value');
                $this->assertTrue(is_array($options['json']), 'Json must be an array');
                $this->assertEquals([
                    'name' => 'Fruit',
                    'type' => CustomFieldDefinition::TEXT,
                ], $options['json']);

                return true;
            }),
            $expectedResponse
        );

        $response = $this->request->send();

        $this->assertInstanceOf(
            CustomFieldsCreateResponse::class,
            $response,
            'Create request must return own response'
        );
        $customField = $response->data();
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(10, $customField->getId());
        $this->assertEquals([], $customField->options()->toArray());
        $this->assertEquals('text', $customField->getType());
        $this->assertEquals('Fruit', $customField->getName());
    }

    public function testConstructCustomField(): void
    {
        $request = new CustomFieldsCreateRequest($this->apiStub, new CustomFieldDefinition('test'));
        $this->assertNotNull($request->customField());
        $this->assertEquals('test', $request->customField()->getName(), 'Custom field is not same as passed');
    }
}
