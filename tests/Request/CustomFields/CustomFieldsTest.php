<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Exceptions\JsonDataMissingException;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\CustomFields;
use SmartEmailing\v3\Request\CustomFields\Create\Request as CreateRequest;
use SmartEmailing\v3\Request\CustomFields\Create\Response as CreateResponse;
use SmartEmailing\v3\Request\CustomFields\Search\Request as SearchRequest;
use SmartEmailing\v3\Request\CustomFields\Search\Response as SearchResponse;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class CustomFieldsTest extends ApiStubTestCase
{
    /**
     * @var CustomFields
     */
    protected $fields;

    protected function setUp(): void
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
        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testSearchRequest()
    {
        $request = $this->fields->searchRequest(1, 10);

        $this->assertInstanceOf(SearchRequest::class, $request);
        $this->assertEquals(1, $request->page);
        $this->assertEquals(10, $request->limit);
    }

    public function testSearchFailOnData()
    {
        // The exact endpoint test are in specific tests for the request
        // Checks if request is called in the send method
        $this->stubClientResponse(null, null, null);

        try {
            $response = $this->fields->search();
            $this->assertInstanceOf(SearchResponse::class, $response);
        } catch (JsonDataMissingException $exception) {
            $this->assertEquals("The JSON response is missing 'data' value", $exception->getMessage());
        }
    }

    public function testExists()
    {
        // The exact endpoint test are in specific tests for the request
        // Checks if request is called in the send method

        $this->defaultReturnResponse = Utils::streamFor('{
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
        $this->stubClientResponse(null, null, null);

        $customField = $this->fields->exists('test');

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomField::class, $customField);
        $this->assertEquals(1, $customField->id);
    }
}
