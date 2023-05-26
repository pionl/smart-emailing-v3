<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contacts\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchFilters;
use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchRequest;
use SmartEmailing\v3\Endpoints\Contacts\Search\ContactsSearchResponse;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class RequestTest extends ApiStubTestCase
{
    protected ContactsSearchRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ContactsSearchRequest($this->apiStub);
    }

    public function testDefaultEndpoint(): void
    {
        $this->expectClientRequest('contacts', 'GET', $this->callback(function ($value): bool {
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
        }), $this->createDefaultResponse());
        $this->request->send();
    }

    public function testFilteredEndpoint(): void
    {
        $this->request->setPage(2, 50)
            ->select(['name', 'emailaddress']);

        // Apply filters
        $this->request->filter()
            ->byGender(Contact::MALE);

        $this->expectClientRequest('contacts', 'GET', $this->callback(function ($value): bool {
            $this->assertTrue(is_array($value), 'Options must return array');
            $this->assertArrayHasKey('query', $value);

            // The query parameters to send
            $query = $value['query'];
            $this->assertArrayHasKey('limit', $query);
            $this->assertArrayHasKey('offset', $query);
            $this->assertEquals(50, $query['offset'], 'The second page should the limit value');
            $this->assertEquals(50, $query['limit'], 'The default limit should be 100');
            $this->assertEquals(Contact::MALE, $query['gender'], 'There should by gender filter');
            $this->assertEquals('name,emailaddress', $query['select']);
            $this->assertCount(4, $query, 'Default query should have only limit and offset');

            return true;
        }), $this->createDefaultResponse());

        $this->request->send();
    }

    public function testResponseEndpoint(): void
    {
        $this->expectClientRequest(null, null, null, $this->createDefaultResponse());

        $response = $this->request->send();

        $this->assertInstanceOf(ContactsSearchResponse::class, $response);
        $this->assertTrue(is_array($response->data()));
        $this->assertCount(2, $response->data());

        /** @var Contact $contact */
        foreach ($response->data() as $contact) {
            $this->assertInstanceOf(Contact::class, $contact);
            $this->assertNotNull($contact->name);
            $this->assertNotNull($contact->gender);
            $this->assertNotNull($contact->id);
        }

        /** @var Contact $contact */
        $contact = $response->data()[0];
        $this->assertEquals(Contact::MALE, $contact->gender);
        $this->assertEquals(1, $contact->id);
        $this->assertEquals('John', $contact->name);
        $this->assertEquals(770, $contact->contactList()->toArray()[0]->id);

        $this->assertEquals(8, $response->meta()->total_count);
    }

    public function testExpandedResponseEndpoint(): void
    {
        $this->expectClientRequest(null, null, null, $this->createExpandedDefaultResponse());

        $response = $this->request->expandCustomFields()
            ->send();

        /** @var Contact $contact */
        $contact = $response->data()[0];
        $this->assertEquals([12, 15, 18], $contact->customFields()->getById(4)->options);
    }

    public function testQuerySort(): void
    {
        $this->createQueryValue('sortBy', ['name'], 'sort', 'sort');
    }

    public function testQueryExpand(): void
    {
        $this->createQueryValue('expandBy', ['customfields'], 'expand', 'expand');
    }

    public function testQueryExpandFail(): void
    {
        try {
            $this->createQueryValue('expandBy', ['test'], 'expand', 'expand');
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertEquals('These values are not allowed: test', $invalidFormatException->getMessage());
        }
    }

    public function testQuerySelect(): void
    {
        $this->createQueryValue('select', ['name'], 'select', 'select');
    }

    public function testQueryFilterById(): void
    {
        $this->createQueryValue('byId', '10', 'id', 'id', $this->request->filter());
    }

    public function testQueryFilterByName(): void
    {
        $this->createQueryValue('byName', 'test', 'name', 'name', $this->request->filter());
    }

    public function testQueryFilterByGender(): void
    {
        $this->createQueryValue('byGender', Contact::MALE, 'gender', 'gender', $this->request->filter());
    }

    public function testQueryFilterByGenderFail(): void
    {
        try {
            $this->createQueryValue('byGender', 'test', 'gender', 'gender', $this->request->filter());
            $this->fail('The value is not valid. Should raise an exception');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertStringContainsString("Value 'test' not allowed", $invalidFormatException->getMessage());
        }
    }

    /**
     * Tests the setter method + query result
     *
     * @param mixed                $value
     * @param ContactsSearchFilters|ContactsSearchRequest|null $setAndGetObject object that will be used for setting/getting the value. Default is
     * Request
     */
    protected function createQueryValue(
        string $setMethod,
        $value,
        string $getProperty,
        string $queryKey,
        $setAndGetObject = null
    ): void {
        if ($setAndGetObject === null) {
            $setAndGetObject = $this->request;
        }

        // Set the value
        $setAndGetObject->{$setMethod}($value);

        // Run the query
        $query = $this->request->query();

        // Check the value if it was set by the function
        if (isset($setAndGetObject->{$getProperty})) {
            $this->assertEquals($value, $setAndGetObject->{$getProperty});
        }

        $this->assertCount(3, $query, sprintf('The query should have 3 items: limit, offset, %s', $queryKey));
        $this->assertEquals(is_array($value) ? implode(',', $value) : $value, $query[$queryKey]);

        // Test override by public property
        $this->request->{$getProperty} = null;
        $this->assertNull($this->request->{$getProperty});
    }

    private function createDefaultResponse(): ResponseInterface
    {
        return $this->createClientResponse('{
            "status": "ok",
            "meta": {
                "total_count": 8,
                "displayed_count": 2,
                "offset": 0,
                "limit": 2
            },
            "data": [
                {
                    "company": null,
                    "street": null,
                    "country": null,
                    "id": 1,
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
                },
                {
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
            ]
        }');
    }

    private function createExpandedDefaultResponse(): ResponseInterface
    {
        return $this->createClientResponse('{
            "status": "ok",
            "meta": {
                "total_count": 8,
                "displayed_count": 2,
                "offset": 0,
                "limit": 2
            },
            "data": [
                {
                    "company": null,
                    "street": null,
                    "country": null,
                    "id": 1,
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
                },
                {
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
            ]
        }');
    }
}
