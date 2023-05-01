<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Get;

use GuzzleHttp\Psr7\Utils;
use SmartEmailing\v3\Endpoints\CustomFields\Get\CustomFieldsGetRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    public function testGet()
    {
        $this->defaultReturnResponse = Utils::streamFor('{
            "status": "ok",
            "meta": [],
            "data": {
                "id": 2,
                "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=2",
                "name": "not correct",
                "type": "checkbox"
            }
        }');
        $customField = $this->request()
            ->send()
            ->data();

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(2, $customField->id);
    }

    public function testNotExists()
    {
        $this->defaultReturnResponse = Utils::streamFor('{
            "status": "error",
            "meta": [],
            "message": "error"
        }');

        $this->expectException(RequestException::class);
        $this->request()
            ->send()
            ->data();
    }

    protected function request()
    {
        $this->stubClientResponse('customfield/2', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            return true;
        }));
        return new CustomFieldsGetRequest($this->apiStub, 2);
    }
}
