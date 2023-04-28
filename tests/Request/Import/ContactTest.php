<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Request\Import\ContactList;
use SmartEmailing\v3\Request\Import\CustomField;
use SmartEmailing\v3\Request\Import\Purpose;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ContactTest extends BaseTestCase
{
    /**
     * @var Contact
     */
    protected $contact;

    protected function setUp(): void
    {
        $this->contact = new Contact('email@gmail.com');
    }

    public function testToArray()
    {
        $this->assertNotCount(1, $this->contact->toArray(), "toArray shouldn't have filtered values");
    }

    /**
     * Test that only email will be returned - array filter works
     */
    public function testToJSONSingleItem()
    {
        $haystack = $this->contact->jsonSerialize();
        $this->assertCount(1, $haystack, 'The array should be filtered from null or empty array values');
    }

    /**
     * Tests if the notes are set and toArray returns 2 items
     */
    public function testToJSONMultiple()
    {
        $this->contact->setNotes('test');
        $haystack = $this->contact->jsonSerialize();
        $this->assertCount(2, $haystack, 'There should be 2 values - email and note');
    }

    public function testSetGender()
    {
        $this->contact->setGender('M');
        $this->assertEquals('M', $this->contact->gender);
    }

    public function testSetGenderNull()
    {
        $this->contact->setGender(null);
        $this->assertNull($this->contact->gender);
    }

    /**
     * Test that gender will raise exception if invalid format is passed
     */
    public function testSetGenderInvalid()
    {
        try {
            $this->contact->setGender('G');
            $this->fail('Set gender should raise InvalidFormatException if invalid');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertStringStartsWith(
                "Value 'G'",
                $invalidFormatException->getMessage(),
                'The exception should contain passed value'
            );
        }
    }

    public function testSetBlacklisted()
    {
        $this->assertEquals(
            1,
            $this->contact->setBlacklisted(true)
->blacklisted,
            'Bool value should be converted to int'
        );
        $this->assertEquals(
            0,
            $this->contact->setBlacklisted(false)
->blacklisted,
            'Bool value should be converted to int'
        );
        $this->assertEquals(1, $this->contact->setBlacklisted(1)->blacklisted);
    }

    public function testSetNameDay()
    {
        $this->assertEquals(
            '2010-12-13 00:00:00',
            $this->contact->setNameDay('2010-12-13')
->nameDay,
            'The date should be converted'
        );
        $this->assertEquals(
            '2010-12-13',
            $this->contact->setNameDay('2010-12-13', false)
->nameDay,
            'We should be able to disable converting'
        );
        $this->assertEquals('2010-12-13', $this->contact->setNameDay('2010-12-13', false) ->nameDay);

        $this->assertEquals(
            date('Y-m-d H:i:s', strtotime('+1 month')),
            $this->contact->setNameDay('+1 month')
->nameDay
        );
        $this->assertCount(2, $this->contact->jsonSerialize(), 'There should be 2 values - email and date');
    }

    public function testJsonSerialize()
    {
        $this->assertCount(1, $this->contact->jsonSerialize(), 'There should be 1 value - email');
    }

    public function testAddField()
    {
        $this->contact->customFields()
            ->add(new CustomField(1));
        $this->assertCount(1, $this->contact->customFields()->toArray(), 'There should be 1 field');
        $this->assertEquals(1, $this->contact->customFields()->get(0)->id);
    }

    public function testAddFieldUnique()
    {
        $this->contact->customFields()
            ->add(new CustomField(1));
        $this->contact->customFields()
            ->add(new CustomField(1));
        $this->contact->customFields()
            ->add(new CustomField(2));
        $this->assertCount(2, $this->contact->customFields()->toArray(), 'There should be 2 fields - unique only');
        $this->assertEquals(1, $this->contact->customFields()->get(0)->id);
    }

    public function testCreateField()
    {
        $this->contact->customFields()
            ->create(1, 'test2', [1]);
        $this->assertCount(1, $this->contact->customFields()->toArray(), 'There should be 1 field');

        /** @var CustomField $field */
        $field = $this->contact->customFields()
            ->get(0);
        $this->assertEquals(1, $field->id);
        $this->assertEquals('test2', $field->value);
        $this->assertEquals([1], $field->options);
    }

    public function testAddList()
    {
        $this->contact->contactList()
            ->add(new ContactList(1));
        $this->assertCount(1, $this->contact->contactList()->toArray(), 'There should be 1 field');
        $this->assertEquals(1, $this->contact->contactList()->get(0)->id);
    }

    public function testCreateList()
    {
        $this->contact->contactList()
            ->create(1, ContactList::REMOVED);
        $this->assertCount(1, $this->contact->contactList()->toArray(), 'There should be 1 field');

        /** @var ContactList $list */
        $list = $this->contact->contactList()
            ->get(0);
        $this->assertEquals(1, $list->id);
        $this->assertEquals(ContactList::REMOVED, $list->status);
    }

    public function testCreateListUnique()
    {
        // This will be stored first
        $this->contact->contactList()
            ->create(1, ContactList::REMOVED);
        // This value will be ignores
        $this->contact->contactList()
            ->create(1, ContactList::UNSUBSCRIBED);
        $this->contact->contactList()
            ->create(2, ContactList::REMOVED);
        $this->assertCount(2, $this->contact->contactList()->toArray(), 'There should be 2 unique fields');

        /** @var ContactList $list */
        $list = $this->contact->contactList()
            ->get(0);
        $this->assertEquals(1, $list->id);
        $this->assertEquals(ContactList::REMOVED, $list->status);
    }

    public function testAddPurpose()
    {
        // This will be stored first
        $this->contact->purposes()
            ->create(1);
        // This value will be ignores
        $this->contact->purposes()
            ->create(1, '1991-06-17 00:00:00', '1998-02-22 05:45:00');
        $this->contact->purposes()
            ->create(2, '1991-06-17 00:00:00', '1998-02-22 05:45:00');
        $this->assertCount(2, $this->contact->purposes()->toArray(), 'There should be 2 unique fields');

        /** @var Purpose $list */
        $list = $this->contact->purposes()
            ->get(0);
        $this->assertEquals(1, $list->id);
        $this->assertEquals(null, $list->valid_to);

        $list = $this->contact->purposes()
            ->get(1);
        $this->assertEquals(2, $list->id);
        $this->assertEquals('1998-02-22 05:45:00', $list->valid_to);
    }

    public function testJson()
    {
        $this->contact->customFields()
            ->create(1, 'test2', [1]);
        $this->contact->contactList()
            ->create(1, ContactList::REMOVED);
        $this->contact->setGender('M');

        $json = json_encode($this->contact->jsonSerialize(), JSON_THROW_ON_ERROR);

        $this->assertEquals(
            '{"emailaddress":"email@gmail.com","gender":"M","contactlists":[{"id":1,"status":"removed"}],"customfields":[{"id":1,"options":[1],"value":"test2"}]}',
            $json
        );
    }
}
