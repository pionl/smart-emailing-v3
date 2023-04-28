<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Request\Import;

use Exception;
use SmartEmailing\v3\Request\Import\CustomField;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;
use TypeError;

class CustomFieldTest extends BaseTestCase
{
    /**
     * @var CustomField
     */
    protected $field;

    protected function setUp(): void
    {
        $this->field = new CustomField(12);
    }

    public function testConstruct()
    {
        $this->assertEquals(12, $this->field->id);
    }

    public function testConstructNumeric()
    {
        $this->assertEquals(13, (new CustomField('13'))->id);
    }

    public function testSetIdNumeric()
    {
        $this->assertEquals(13, $this->field->setId('13')->id);
    }

    public function testSetValue()
    {
        $this->assertEquals('test', $this->field->setValue('test')->value);
    }

    public function testSetOptions()
    {
        $this->assertEquals([1, 2], $this->field->setOptions([1, 2])->options);
    }

    public function testNonArray()
    {
        try {
            $this->field->setOptions('test'); /** @phpstan-ignore-line */
            $this->fail('The options should require an array and raise warning');
        } catch (Exception|TypeError $exception) {
            $this->assertStringContainsString('type array', $exception->getMessage());
        }
    }

    public function testAddOption()
    {
        $this->field->addOption(1);
        $this->field->addOption(2);
        $this->assertEquals([1, 2], $this->field->options);
    }
}
