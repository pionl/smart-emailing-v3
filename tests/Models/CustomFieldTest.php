<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use Exception;
use SmartEmailing\v3\Models\CustomFieldValue;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;
use TypeError;

class CustomFieldTest extends BaseTestCase
{
    protected CustomFieldValue $field;

    protected function setUp(): void
    {
        $this->field = new CustomFieldValue(12);
    }

    public function testConstruct(): void
    {
        $this->assertEquals(12, $this->field->getId());
    }

    public function testConstructNumeric(): void
    {
        $this->assertEquals(13, (new CustomFieldValue('13'))->getId());
    }

    public function testSetIdNumeric(): void
    {
        $this->assertEquals(13, $this->field->setId('13')->getId());
    }

    public function testSetValue(): void
    {
        $this->assertEquals('test', $this->field->setValue('test')->getValue());
    }

    public function testSetOptions(): void
    {
        $this->assertEquals([1, 2], $this->field->setOptions([1, 2])->getOptions());
    }

    public function testNonArray(): void
    {
        try {
            $this->field->setOptions('test'); /** @phpstan-ignore-line */
            $this->fail('The options should require an array and raise warning');
        } catch (Exception|TypeError $exception) {
            $this->assertStringContainsString('type array', $exception->getMessage());
        }
    }

    public function testAddOption(): void
    {
        $this->field->addOption(1);
        $this->field->addOption(2);
        $this->assertEquals([1, 2], $this->field->getOptions());
    }
}
