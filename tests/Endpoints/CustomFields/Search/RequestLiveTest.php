<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Search;

use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchResponse;
use SmartEmailing\v3\Endpoints\CustomFields\Search\Request;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class RequestLiveTestCase extends BaseTestCase
{
    /**
     * @var CustomFieldsSearchRequest
     */
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()
            ->customFields()
            ->searchRequest();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(CustomFieldsSearchRequest::class, $this->request);
    }

    public function testSend()
    {
        $this->assertEquals(1, $this->request->page);
        //$this->request->filter()->byName('Source');
        return;

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(
            CustomFieldsSearchResponse::class,
            $response,
            'Search request must return own response'
        );
        $this->assertEquals(CustomFieldsSearchResponse::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());

        // Check custom-field
        $data = $response->data();

        $this->assertTrue(is_array($data));

        /** @var CustomFieldDefinition $customField */
        foreach ($data as $customField) {
            $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
            $this->assertNotNull($customField->name);
            $this->assertNotNull($customField->type);
            $this->assertNotNull($customField->id);
        }
    }
}
