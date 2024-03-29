<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Create;

use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateResponse;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected CustomFieldsCreateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()
            ->customFields()
            ->createRequest();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(CustomFieldsCreateRequest::class, $this->request);
    }

    public function testSend(): void
    {
        $this->request->setCustomField(new CustomFieldDefinition('test', CustomFieldDefinition::TEXT));
        $this->assertNotNull($this->request->customField());

        // Comment if you want to send request
        $this->markTestSkipped();

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(
            CustomFieldsCreateResponse::class,
            $response,
            'Create request must return own response'
        );
        $this->assertEquals(CustomFieldsCreateResponse::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());

        // Check custom-field
        $customField = $response->data();
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);

        $this->assertTrue(is_numeric($customField->id));
        $this->assertNull($customField->options);
        $this->assertEquals('text', $customField->type);
        $this->assertEquals('test', $customField->name);
    }
}
