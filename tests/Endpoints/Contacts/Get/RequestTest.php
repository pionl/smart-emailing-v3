<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contacts\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\Contacts\Get\ContactsGetRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    public function testGet(): void
    {
        $response = $this->createClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "company": null,
                "street": null,
                "country": null,
                "id": 2,
                "created": "2015-08-11 14:52:49",
                "updated": null,
                "last_clicked": null,
                "softbounced": 0,
                "nameday": null,
                "hardbounced": 0,
                "realname": null,
                "emailaddress": "testmail_123@g4it.cz",
                "surname": null,
                "cellphone": null,
                "birthday": null,
                "name": "John",
                "salution": null,
                "domain": "g4it.cz",
                "customfields_url": "https://app.smartemailing.cz/api/v3/contact-customfields?contact_id=29678",
                "contactlists": [
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 30286,
                        "score_clicks": null,
                        "added": "2016-02-15 23:28:16",
                        "score_opens": null,
                        "contactlist_id": 770,
                        "contact_id": 29678
                    },
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 36233,
                        "score_clicks": null,
                        "added": "2016-09-13 00:25:25",
                        "score_opens": null,
                        "contactlist_id": 779,
                        "contact_id": 29678
                    },
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 45540,
                        "score_clicks": null,
                        "added": "2016-09-20 04:04:31",
                        "score_opens": null,
                        "contactlist_id": 782,
                        "contact_id": 29678
                    }
                ],
                "titlesbefore": null,
                "blacklisted": 0,
                "last_opened": null,
                "town": null,
                "gender": "M",
                "titlesafter": null,
                "postalcode": null,
                "affilid": null,
                "language": "cs_CZ",
                "phone": null,
                "guid": "df2198a1-4027-11e5-8cf3-002590a1e85a",
                "notes": null,
                "preferredDeliveryTime": null
            }
        }');
        $contact = $this->request($response)
            ->send()
            ->data();

        $this->assertTrue(is_object($contact), 'The item is in the source');
        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(2, $contact->id);
        $this->assertEquals('testmail_123@g4it.cz', $contact->emailAddress);
    }

    public function testGetExpandCustomFields(): void
    {
        $response = $this->createClientResponse('{
            "status": "ok",
            "meta": [],
            "data": {
                "company": null,
                "street": null,
                "country": null,
                "id": 2,
                "created": "2015-08-11 14:52:49",
                "updated": null,
                "last_clicked": null,
                "softbounced": 0,
                "nameday": null,
                "hardbounced": 0,
                "realname": null,
                "emailaddress": "testmail_123@g4it.cz",
                "surname": null,
                "cellphone": null,
                "birthday": null,
                "name": "John",
                "salution": null,
                "domain": "g4it.cz",
                "customfields": [
                    {
                        "id": 247982,
                        "contact_id": 29678,
                        "customfield_id": 4,
                        "customfield_options_id": 12,
                        "value": null
                    },
                    {
                        "id": 247982,
                        "contact_id": 29678,
                        "customfield_id": 4,
                        "customfield_options_id": 15,
                        "value": null
                    },
                    {
                        "id": 247982,
                        "contact_id": 29678,
                        "customfield_id": 4,
                        "customfield_options_id": 18,
                        "value": null
                    },
                    {
                        "id": 247983,
                        "contact_id": 29678,
                        "customfield_id": 8,
                        "customfield_options_id": null,
                        "value": "Hello world"
                    }
                ],
                "contactlists": [
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 30286,
                        "score_clicks": null,
                        "added": "2016-02-15 23:28:16",
                        "score_opens": null,
                        "contactlist_id": 770,
                        "contact_id": 29678
                    },
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 36233,
                        "score_clicks": null,
                        "added": "2016-09-13 00:25:25",
                        "score_opens": null,
                        "contactlist_id": 779,
                        "contact_id": 29678
                    },
                    {
                        "status": "confirmed",
                        "updated": null,
                        "id": 45540,
                        "score_clicks": null,
                        "added": "2016-09-20 04:04:31",
                        "score_opens": null,
                        "contactlist_id": 782,
                        "contact_id": 29678
                    }
                ],
                "titlesbefore": null,
                "blacklisted": 0,
                "last_opened": null,
                "town": null,
                "gender": "M",
                "titlesafter": null,
                "postalcode": null,
                "affilid": null,
                "language": "cs_CZ",
                "phone": null,
                "guid": "df2198a1-4027-11e5-8cf3-002590a1e85a",
                "notes": null,
                "preferredDeliveryTime": null
            }
        }');
        $contact = $this->request($response)
            ->expandCustomFields()
            ->send()
            ->data();

        $this->assertTrue(is_object($contact), 'The item is in the source');
        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(2, $contact->id);
        $this->assertEquals('testmail_123@g4it.cz', $contact->emailAddress);
        $this->assertEquals([12, 15, 18], $contact->customFields()->getById(4)->options);
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

    protected function request(ResponseInterface $response): ContactsGetRequest
    {
        $this->expectClientRequest('contacts/2', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            return true;
        }), $response);
        return new ContactsGetRequest($this->apiStub, 2);
    }
}
