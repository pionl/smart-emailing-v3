<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields;

use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\CustomFields;
use SmartEmailing\v3\Request\CustomFields\Requests\CreateRequest;
use SmartEmailing\v3\Request\CustomFields\Responses\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CustomFieldsTest extends ApiStubTestCase
{
    /**
     * @var CustomFields
     */
    protected $fields;

    protected function setUp()
    {
        parent::setUp();
        $this->fields = new CustomFields($this->apiStub);
    }

    public function testCreateRequest()
    {
        $request = $this->fields->createRequest();

        $this->assertInstanceOf(CreateRequest::class, $request);
        $this->assertNull($request->customField());
    }

    public function testCreateRequestCustomField()
    {
        $request = $this->fields->createRequest(new CustomField('test'));

        $this->assertInstanceOf(CreateRequest::class, $request);
        $this->assertNotNull($request->customField());
    }

    public function testCreate()
    {
        // The exact endpoint test are in specific tests for the request
        // Checks if request is called in the send method
        $this->stubClientResponse(null, null, null);
        $response = $this->fields->create(new CustomField('test'));
        $this->assertInstanceOf(Response::class, $response);
    }
}