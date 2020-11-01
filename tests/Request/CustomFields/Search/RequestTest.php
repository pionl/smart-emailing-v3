<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Search;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Search\Filters;
use SmartEmailing\v3\Request\CustomFields\Search\Request;
use SmartEmailing\v3\Request\CustomFields\Search\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    /**
     * @var Request
     */
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->request = new Request($this->apiStub);

        //region Default response setup
        $this->defaultReturnResponse = '{
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
        }';
        //endregion
    }

    /**
     * Tests the setter method + query result
     *
     * @param string               $setMethod
     * @param mixed                $value
     * @param string               $getProperty
     * @param string               $queryKey
     * @param Filters|Request|null $setAndGetObject object that will be used for setting/getting the value. Default is
     *                                              Request
     */
    protected function createQueryValue($setMethod, $value, $getProperty, $queryKey, $setAndGetObject = null)
    {
        if (is_null($setAndGetObject)) {
            $setAndGetObject = $this->request;
        }

        // Set the value
        $setAndGetObject->{$setMethod}($value);

        // Run the query
        $query = $this->request->query();

        // Check the value if it was set by the function
        $this->assertEquals($value, $setAndGetObject->{$getProperty});

        $this->assertCount(3, $query, "The query should have 3 items: limit, offset, {$queryKey}");
        $this->assertEquals($value, $query[$queryKey]);

        // Test override by public property
        $this->request->{$getProperty} = null;
        $this->assertNull($this->request->{$getProperty});
    }

    //region Test endpoint
    public function testDefaultEndpoint()
    {
        $this->createEndpointTest($this->request, 'customfields', 'GET', $this->callback(function ($value) {
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
        }));
    }

    public function testFilteredEndpoint()
    {
        $this->request->setPage(2)
            ->select('name, type')
            ->limit(50);

        // Apply filters
        $this->request->filter()->byType(CustomField::CHECKBOX);

        $this->createEndpointTest($this->request, 'customfields', 'GET', $this->callback(function ($value) {
            $this->assertTrue(is_array($value), 'Options must return array');
            $this->assertArrayHasKey('query', $value);

            // The query parameters to send
            $query = $value['query'];
            $this->assertArrayHasKey('limit', $query);
            $this->assertArrayHasKey('offset', $query);
            $this->assertEquals(50, $query['offset'], 'The second page should the limit value');
            $this->assertEquals(50, $query['limit'], 'The default limit should be 100');
            $this->assertEquals(CustomField::CHECKBOX, $query['type'], 'There should by type filter');
            $this->assertEquals('name, type', $query['select']);
            $this->assertCount(4, $query, 'Default query should have only limit and offset');

            return true;
        }));
    }

    public function testResponseEndpoint()
    {
        /** @var Response $response */
        $response = $this->createEndpointTest($this->request, null, null, null);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue(is_array($response->data()));
        $this->assertCount(2, $response->data());

        /** @var CustomField $customField */
        foreach ($response->data() as $customField) {
            $this->assertInstanceOf(CustomField::class, $customField);
            $this->assertNotNull($customField->name);
            $this->assertNotNull($customField->type);
            $this->assertNotNull($customField->id);
        }

        $this->assertEquals('select', $response->data()[0]->type);
        $this->assertEquals(1, $response->data()[0]->id);
        $this->assertEquals('my select', $response->data()[0]->name);

        $this->assertEquals(8, $response->meta()->total_count);

    }
    //endregion

    //region Test query
    public function testQuerySort()
    {
        $this->createQueryValue('sortBy', 'type', 'sort', 'sort');
    }

    public function testQueryExpand()
    {
        $this->createQueryValue('expandBy', 'customfield_options', 'expand', 'expand');
    }

    public function testQueryExpandFail()
    {
        try {
            $this->createQueryValue('expandBy', 'test', 'expand', 'expand');
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $exception) {
            $this->assertEquals("Value 'test' not allowed: customfield_options", $exception->getMessage());
        }
    }

    public function testQuerySelect()
    {
        $this->createQueryValue('select', 'name', 'select', 'select');
    }

    public function testQueryFilterById()
    {
        $this->createQueryValue('byId', '10', 'id', 'id', $this->request->filter());
    }

    public function testQueryFilterByName()
    {
        $this->createQueryValue('byName', 'test', 'name', 'name', $this->request->filter());
    }

    public function testQueryFilterByType()
    {
        $this->createQueryValue('byType', CustomField::CHECKBOX, 'type', 'type', $this->request->filter());
    }

    public function testQueryFilterByTypeFail()
    {
        try {
            $this->createQueryValue('byType', 'test', 'type', 'type', $this->request->filter());
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $exception) {
            $this->assertContains("Value 'test' not allowed", $exception->getMessage());
        }
    }
    //endregion


}
