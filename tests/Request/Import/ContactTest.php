<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Tests\BaseTestCase;

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

    /**
     * Test that only email will be returned - array filter works
     */
    public function testToArraySingleItem()
    {
        $haystack = $this->contact->toArray();
        $this->assertCount(1, $haystack, 'The array should be filtered from null or empty array values');
    }

    /**
     * Tests if the notes are set and toArray returns 2 items
     */
    public function testToArrayMultiple()
    {
        $this->contact->setNotes('test');
        $haystack = $this->contact->toArray();
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
        $this->assertCount(2, $this->contact->toArray(), 'There should be 2 values - email and date');
    }

    public function testJsonSerialize()
    {
        $this->assertCount(1, $this->contact->jsonSerialize(), 'There should be 2 values - email and date');
    }
}