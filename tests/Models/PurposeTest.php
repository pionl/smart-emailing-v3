<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use Exception;
use SmartEmailing\v3\Models\Purpose;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class PurposeTest extends BaseTestCase
{
    protected Purpose $field;

    protected function setUp(): void
    {
        $this->field = new Purpose(12);
    }

    public function testConstruct(): void
    {
        $this->assertEquals(12, $this->field->id);
    }

    public function testConstructNumeric(): void
    {
        $this->assertEquals(13, (new Purpose('13'))->id);
    }

    public function testSetIdNumeric(): void
    {
        $this->assertEquals(13, $this->field->setId('13')->id);
    }

    public function testSetValidFrom(): void
    {
        $this->assertEquals('1991-06-17 00:00:00', $this->field->setValidFrom('1991-06-17 00:00:00')->valid_from);
    }

    public function testSetValidTo(): void
    {
        $this->assertEquals('1991-06-17 00:00:00', $this->field->setValidTo('1991-06-17 00:00:00')->valid_to);
    }

    public function testDateTimeFormat(): void
    {
        try {
            $this->field->setValidFrom('1991-06-17');
            $this->fail('The options should require format YYYY-MM-DD HH:MM:SS');
        } catch (Exception $exception) {
            $this->assertStringContainsString('Invalid date and time format', $exception->getMessage());
        }

        try {
            $this->field->setValidFrom('test');
            $this->fail('The options should require format YYYY-MM-DD HH:MM:SS');
        } catch (Exception $exception) {
            $this->assertStringContainsString('Invalid date and time format', $exception->getMessage());
        }
    }
}
