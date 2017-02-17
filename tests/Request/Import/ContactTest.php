<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Request\Import\ContactList;
use SmartEmailing\v3\Request\Import\CustomField;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ContactTest extends BaseTestCase
{
    /**
     * @var Contact
     */
    protected $contact;

    public function setUp()
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
        } catch (InvalidFormatException $exception) {
            $this->assertStringStartsWith("Value 'G'", $exception->getMessage(), 'The exception should contain passed value');
        }
    }

    public function testSetBlacklisted()
    {
        $this->assertEquals(1, $this->contact->setBlacklisted(true)->blacklisted, 'Bool value should be converted to int');
        $this->assertEquals(0, $this->contact->setBlacklisted(false)->blacklisted, 'Bool value should be converted to int');
        $this->assertEquals(1, $this->contact->setBlacklisted(1)->blacklisted);
    }

    public function testSetNameDay()
    {
        $this->assertEquals(
            '2010-12-13 00:00:00', $this->contact->setNameDay('2010-12-13')->nameDay, 'The date should be converted'
        );
        $this->assertEquals(
            '2010-12-13', $this->contact->setNameDay('2010-12-13', false)->nameDay, 'We should be able to disable converting'
        );
        $this->assertEquals(
            '2010-12-13', $this->contact->setNameDay('2010-12-13', false)->nameDay
        );

        $this->assertEquals(
            date('Y-m-d H:i:s', strtotime('+1 month')), $this->contact->setNameDay('+1 month')->nameDay
        );
        $this->assertCount(2, $this->contact->jsonSerialize(), 'There should be 2 values - email and date');
    }

    public function testJsonSerialize()
    {
        $this->assertCount(1, $this->contact->jsonSerialize(), 'There should be 1 value - email');
    }

    public function testAddField()
    {
        $this->contact->customFields()->add(new CustomField(1));
        $this->assertCount(1, $this->contact->customFields()->toArray(), 'There should be 1 field');
        $this->assertEquals(1, $this->contact->customFields()->get(0)->id);
    }

    public function testCreateField()
    {
        $this->contact->customFields()->create(1, 'test2', [1]);
        $this->assertCount(1, $this->contact->customFields()->toArray(), 'There should be 1 field');

        /** @var CustomField $field */
        $field = $this->contact->customFields()->get(0);
        $this->assertEquals(1, $field->id);
        $this->assertEquals('test2', $field->value);
        $this->assertEquals([1], $field->options);
    }

    public function testAddList()
    {
        $this->contact->contactList()->add(new ContactList(1));
        $this->assertCount(1, $this->contact->contactList()->toArray(), 'There should be 1 field');
        $this->assertEquals(1, $this->contact->contactList()->get(0)->id);
    }

    public function testCreateList()
    {
        $this->contact->contactList()->create(1, ContactList::REMOVED);
        $this->assertCount(1, $this->contact->contactList()->toArray(), 'There should be 1 field');

        /** @var ContactList $list */
        $list = $this->contact->contactList()->get(0);
        $this->assertEquals(1, $list->id);
        $this->assertEquals(ContactList::REMOVED, $list->status);
    }

    public function testJson()
    {
        $this->contact->customFields()->create(1, 'test2', [1]);
        $this->contact->contactList()->create(1, ContactList::REMOVED);
        $this->contact->setGender('M');

        $json = json_encode($this->contact->jsonSerialize());

        $this->assertEquals(
            '{"emailaddress":"email@gmail.com","gender":"M","contactlists":[{"id":1,"status":"removed"}],"customfields":[{"id":1,"options":[1],"value":"test2"}]}',
            $json
        );
    }
}