<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\Import\ContactList;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ContactListTest extends BaseTestCase
{
    /**
     * @var ContactList
     */
    protected $list;

    protected function setUp()
    {
        $this->list = new ContactList(1);
    }

    public function testConstruct()
    {
        $this->assertEquals(1, $this->list->id);
    }

    public function testSetStatus()
    {
        $this->assertEquals(ContactList::CONFIRMED, $this->list->status);
        $this->assertEquals(ContactList::REMOVED, $this->list->setStatus(ContactList::REMOVED)->status);
    }

    public function testSetInvalidStatus()
    {
        try {
            $this->list->setStatus('test');
        } catch (InvalidFormatException $exception) {
            $this->assertStringStartsWith("Value 'test'", $exception->getMessage());
        }
    }

    public function testToArray()
    {
        $this->assertEquals([
            'id' => 1,
            'status' => ContactList::CONFIRMED
        ], $this->list->toArray());
    }

    public function testJsonSerialize()
    {
        $this->assertEquals([
            'id' => 1,
            'status' => ContactList::CONFIRMED
        ], $this->list->jsonSerialize());
    }
}
