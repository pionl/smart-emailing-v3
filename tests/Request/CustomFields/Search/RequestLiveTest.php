<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Search;

use SmartEmailing\v3\Request\CustomFields\Search\Request;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Search\Response;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class RequestLiveTestCase extends BaseTestCase
{
    /**
     * @var Request
     */
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createApi()->customFields()->searchRequest();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Request::class, $this->request);
    }

    public function testSend()
    {
        $this->assertEquals(1, $this->request->page);
        //$this->request->filter()->byName('Source');
        return;

        $response = $this->request->send();

        // Check http data
        $this->assertInstanceOf(Response::class, $response, 'Search request must return own response');
        $this->assertEquals(Response::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());

        // Check custom-field
        $data = $response->data();

        $this->assertTrue(is_array($data));

        /** @var CustomField $customField */
        foreach ($data as $customField) {
            $this->assertInstanceOf(CustomField::class, $customField);
            $this->assertNotNull($customField->name);
            $this->assertNotNull($customField->type);
            $this->assertNotNull($customField->id);
        }
    }
}
