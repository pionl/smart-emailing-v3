<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Exists;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Exists\Request;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    protected function request()
    {
        $this->stubClientResponse('customfields', 'GET', $this->callback(function ($value) {
            $this->assertTrue(is_array($value), 'Options must return array');
            $this->assertArrayHasKey('query', $value);

            // The query parameters to send
            $query = $value['query'];
            $this->assertArrayHasKey('limit', $query);
            $this->assertArrayHasKey('offset', $query);
            $this->assertEquals(0, $query['offset'], 'Exists wants always first page');
            $this->assertEquals(1, $query['limit'], 'The limit should be only single item');
            $this->assertEquals('test', $query['name']);
            $this->assertCount(3, $query, 'Default query should have only limit and offset and test filter');

            return true;
        }));
        return new Request($this->apiStub, 'test');
    }

    public function testExists()
    {
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
                    "id": 2,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=2",
                    "name": "not correct",
                    "type": "checkbox"
                },
                {
                    "id": 1,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=1",
                    "name": "test",
                    "type": "checkbox"
                }
            ]
        }');
        $customField = $this->request()->exists();

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomField::class, $customField);
        $this->assertEquals(1, $customField->id);
    }

    public function testNotExistsWithResults()
    {
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
                    "id": 2,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=2",
                    "name": "not correct",
                    "type": "checkbox"
                },
                {
                    "id": 1,
                    "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=1",
                    "name": "test 2",
                    "type": "checkbox"
                }
            ]
        }');

        $customField = $this->request()->exists();

        $this->assertFalse($customField, 'The exists should return false if not found');
    }

    public function testNotExistsWithoutResults()
    {
        $this->defaultReturnResponse = Utils::streamFor('{
            "status": "ok",
            "meta": {
                "total_count": 8,
                "displayed_count": 2,
                "offset": 0,
                "limit": 2
            },
            "data": [
            ]
        }');

        $customField = $this->request()->exists();

        $this->assertFalse($customField, 'The exists should return false if not found');
    }
}
