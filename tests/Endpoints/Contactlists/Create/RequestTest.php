<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlists\Create;

use SmartEmailing\v3\Endpoints\Contactlists\Create\ContactlistCreateRequest;
use SmartEmailing\v3\Endpoints\Contactlists\Create\ContactlistCreateResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTestCase extends ApiStubTestCase
{
    protected ContactlistCreateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ContactlistCreateRequest($this->apiStub);
    }

    public function testEndpointAndResponse(): void
    {
        $contactList = new Contactlist();
        $contactList->setName('testing');

        $this->request->setContactList($contactList);

        $expectedResponse = $this->createClientResponse('{
            "status": "created",
            "meta": [],
            "data": {
                "replyto": "replyto@smartemailing.cz",
                "clickRate": null,
                "hidden": 0,
                "alertOut": 0,
                "alertIn": 0,
                "category": null,
                "signature": null,
                "sendername": "Martin Strouhal",
                "segment_id": null,
                "name": "testing",
                "openRate": null,
                "senderemail": "martin@smartemailing.cz",
                "id": 10,
                "notes": null,
                "trackedDefaultFields": "a:0:{}",
                "publicname": "Going public!",
                "created": "2017-06-13 17:55:25"
            }
        }');

        $this->expectClientRequest(
            'contactlists',
            'POST',
            $this->callback(function ($options): bool {
                $this->assertArrayHasKey('json', $options, 'Options must return an json value');
                $this->assertTrue(is_array($options['json']), 'Json must be an array');
                $this->assertEquals([
                    'name' => 'testing',
                ], $options['json']);

                return true;
            }),
            $expectedResponse
        );

        $response = $this->request->send();

        $this->assertInstanceOf(
            ContactlistCreateResponse::class,
            $response,
            'Create request must return own response'
        );
        $customField = $response->data();
        $this->assertInstanceOf(Contactlist::class, $customField);
        $this->assertEquals(10, $customField->id);
        $this->assertEquals('testing', $customField->name);
    }

    public function testConstructCustomField(): void
    {
        $request = new ContactlistCreateRequest($this->apiStub, (new Contactlist())->setName('testing'));
        $this->assertNotNull($request->contactList());
        $this->assertEquals('testing', $request->contactList()->name, 'Custom field is not same as passed');
    }
}
