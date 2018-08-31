<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Request\Import\Purpose;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class PurposeTest extends BaseTestCase
{
    /**
     * @var Purpose
     */
    protected $field;

    protected function setUp()
    {
        $this->field = new Purpose(12);
    }

    public function testConstruct()
    {
        $this->assertEquals(12, $this->field->id);
    }

    public function testConstructNumeric()
    {
        $this->assertEquals(13, (new Purpose('13'))->id);
    }

    public function testSetIdNumeric()
    {
        $this->assertEquals(13, $this->field->setId('13')->id);
    }

    public function testSetValidFrom()
    {
        $this->assertEquals('1991-06-17 00:00:00', $this->field->setValidFrom('1991-06-17 00:00:00')->valid_from);
    }

    public function testSetValidTo()
    {
        $this->assertEquals('1991-06-17 00:00:00', $this->field->setValidTo('1991-06-17 00:00:00')->valid_to);
    }

    public function testDateTimeFormat()
    {
        try {
            $this->field->setValidFrom('1991-06-17');
            $this->fail('The options should require format YYYY-MM-DD HH:MM:SS');
        } catch (\Exception $exception) {
            $this->assertContains('Invalid date and time format', $exception->getMessage());
        }

        try {
            $this->field->setValidFrom('test');
            $this->fail('The options should require format YYYY-MM-DD HH:MM:SS');
        } catch (\Exception $exception) {
            $this->assertContains('Invalid date and time format', $exception->getMessage());
        }
    }
}