<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlists\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\Contactlists\Get\ContactlistGetRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    public function testGet(): void
    {
        $response = $this->createClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "replyto": "martin@smartemailing.cz",
                "clickRate": null,
                "hidden": 0,
                "alertOut": 0,
                "alertIn": 0,
                "category": null,
                "signature": null,
                "sendername": "Martin Strouhal",
                "segment_id": null,
                "name": "martin@smartemailing.cz",
                "openRate": null,
                "senderemail": "martin@smartemailing.cz",
                "id": 2,
                "notes": null,
                "trackedDefaultFields": "a:0:{}",
                "publicname": "martin@smartemailing.cz",
                "created": "2015-07-21 17:55:25"
            }
        }');
        $contactlist = $this->request($response)
            ->send()
            ->data();

        $this->assertTrue(is_object($contactlist), 'The item is in the source');
        $this->assertInstanceOf(Contactlist::class, $contactlist);
        $this->assertEquals(2, $contactlist->id);
        $this->assertEquals('martin@smartemailing.cz', $contactlist->replyTo);
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

    protected function request(ResponseInterface $response): ContactlistGetRequest
    {
        $this->expectClientRequest('contactlists/2', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            return true;
        }), $response);
        return new ContactlistGetRequest($this->apiStub, 2);
    }
}
