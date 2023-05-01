<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\ContactListStatus;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ContactListTest extends BaseTestCase
{
    /**
     * @var ContactListStatus
     */
    protected $list;

    protected function setUp(): void
    {
        $this->list = new ContactListStatus(1);
    }

    public function testConstruct()
    {
        $this->assertEquals(1, $this->list->id);
    }

    public function testSetStatus()
    {
        $this->assertEquals(ContactListStatus::CONFIRMED, $this->list->status);
        $this->assertEquals(ContactListStatus::REMOVED, $this->list->setStatus(ContactListStatus::REMOVED)->status);
    }

    public function testSetInvalidStatus()
    {
        try {
            $this->list->setStatus('test');
        } catch (InvalidFormatException $invalidFormatException) {
            $this->assertStringStartsWith("Value 'test'", $invalidFormatException->getMessage());
        }
    }

    public function testToArray()
    {
        $this->assertEquals([
            'id' => 1,
            'status' => ContactListStatus::CONFIRMED,
        ], $this->list->toArray());
    }

    public function testJsonSerialize()
    {
        $this->assertEquals([
            'id' => 1,
            'status' => ContactListStatus::CONFIRMED,
        ], $this->list->jsonSerialize());
    }
}
