<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Requests;

use SmartEmailing\v3\Request\CustomFields\Requests\CreateRequest;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Responses\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CreateRequestLiveTestCase extends ApiStubTestCase
{
    /**
     * @var CreateRequest
     */
    protected $request;

    protected function setUp()
    {
        parent::setUp();
        $this->request = $this->createApi()->customFields()->createRequest();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(CreateRequest::class, $this->request);
    }

    public function testSend()
    {
        $this->request->setCustomField(new CustomField('test', CustomField::TEXT));
        $this->assertNotNull($this->request->customField());
        return;

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(Response::class, $response, 'Create request must return own response');
        $this->assertEquals(Response::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());

        // Check custom-field
        $customField = $response->data();
        $this->assertInstanceOf(CustomField::class, $customField);

        $this->assertTrue(is_numeric($customField->id));
        $this->assertNull($customField->options);
        $this->assertEquals('text', $customField->type);
        $this->assertEquals('test', $customField->name);
    }
}