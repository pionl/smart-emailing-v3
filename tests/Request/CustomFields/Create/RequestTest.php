<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Create;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Request\CustomFields\Create\Request;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Create\Response;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTestCase extends ApiStubTestCase
{
    /**
     * @var Request
     */
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request($this->apiStub);
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

    public function testEndpointAndResponse()
    {
        $this->request->setCustomField(new CustomField('Fruit', CustomField::TEXT));

        /** @var Response $response */
        $response = $this->createEndpointTest($this->request, 'customfields', 'POST', $this->callback(function ($array) {
            $this->assertTrue(is_array($array), 'Options must return an array');
            $this->assertArrayHasKey('json', $array, 'Options must return an json value');
            $this->assertTrue(is_array($array['json']), 'Json must be an array');
            $this->assertEquals([
                'name' => 'Fruit',
                'type' => CustomField::TEXT
            ], $array['json']);

            return true;
        }));

        $this->assertInstanceOf(Response::class, $response, 'Create request must return own response');
        $customField = $response->data();
        $this->assertInstanceOf(CustomField::class, $customField);
        $this->assertEquals(10, $customField->id);
        $this->assertNull($customField->options);
        $this->assertEquals('text', $customField->type);
        $this->assertEquals('Fruit', $customField->name);
    }

    public function testConstructCustomField()
    {
        $request = new Request($this->apiStub, new CustomField('test'));
        $this->assertNotNull($request->customField());
        $this->assertEquals('test', $request->customField()->name, 'Custom field is not same as passed');
    }
}
