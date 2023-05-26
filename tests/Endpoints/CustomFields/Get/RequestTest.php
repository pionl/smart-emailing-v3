<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\CustomFields\Get\CustomFieldsGetRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    public function testGet(): void
    {
        $response = $this->createClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "id": 2,
                "customfield_options_url": "http://app.stormspire.loc/api/v3/customfield-options?customfield_id=2",
                "name": "not correct",
                "type": "checkbox"
            }
        }');
        $customField = $this->request($response)
            ->send()
            ->data();

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(2, $customField->id);
    }

    public function testGetExpanded(): void
    {
        $response = $this->createClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "id": 2,
                "customfield_options": [
                    {
                        "customfield_id": 1,
                        "id": 1,
                        "order": 0,
                        "name": "Tokyo"
                    },
                    {
                        "customfield_id": 1,
                        "id": 2,
                        "order": 1,
                        "name": "Sydney"
                    },
                    {
                        "customfield_id": 1,
                        "id": 3,
                        "order": 2,
                        "name": "Torino"
                    }
                ],
                "name": "not correct",
                "type": "checkbox"
            }
        }');
        $customField = $this->request($response)
            ->expandCustomFieldOptions()
            ->send()
            ->data();

        $this->assertTrue(is_object($customField), 'The item is in the source');
        $this->assertInstanceOf(CustomFieldDefinition::class, $customField);
        $this->assertEquals(2, $customField->id);
        $this->assertEquals('Tokyo', $customField->options()->get(0)->name);
        $this->assertEquals('Torino', $customField->options()->getById(3)->name);
    }

    public function testNotExists(): void
    {
        $response = $this->createClientResponse('{
            "status": "error",
            "meta": [],
            "message": "error"
        }');

        $this->expectException(RequestException::class);
        $this->request($response)
            ->send()
            ->data();
    }

    protected function request(ResponseInterface $response): CustomFieldsGetRequest
    {
        $this->expectClientRequest('customfield/2', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            return true;
        }), $response);
        return new CustomFieldsGetRequest($this->apiStub, 2);
    }
}
