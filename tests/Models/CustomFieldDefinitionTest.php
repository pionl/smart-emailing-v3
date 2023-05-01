<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Models\CustomFieldValue;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class CustomFieldDefinitionTest extends BaseTestCase
{
    /**
     * @var CustomFieldDefinition
     */
    protected $field;

    protected function setUp(): void
    {
        parent::setUp();
        $this->field = new CustomFieldDefinition();
    }

    public function testEmptyConstruct()
    {
        $this->assertNull($this->field->name);
        $this->assertNull($this->field->type);
        $this->assertNull($this->field->id);
        $this->assertTrue(is_array($this->field->options), 'Options must be an array');
        $this->assertEmpty($this->field->options);
    }

    public function testConstruct()
    {
        $field = new CustomFieldDefinition('Test', CustomFieldDefinition::CHECKBOX);

        $this->assertEquals('Test', $field->name);
        $this->assertEquals(CustomFieldDefinition::CHECKBOX, $field->type);
        $this->assertTrue(is_array($field->options), 'Options must be an array');
        $this->assertEmpty($field->options);
    }

    public function testConstructInvalidType()
    {
        try {
            new CustomFieldDefinition('Test', 'test');
            $this->fail('The construct should use setType method that checks the correct type');
        } catch (InvalidFormatException $invalidFormatException) {
            // Check if all the types are passed
            $this->assertEquals(
                "Value 'test' not allowed: text, textarea, date, checkbox, radio, select",
                $invalidFormatException->getMessage()
            );
        }
    }

    public function testSetType()
    {
        // Test if set
        $this->assertEquals(
            CustomFieldDefinition::CHECKBOX,
            $this->field->setType(CustomFieldDefinition::CHECKBOX)->type
        );
        $this->field->setType(CustomFieldDefinition::TEXT_AREA);
        $this->field->setType(CustomFieldDefinition::DATE);
        $this->field->setType(CustomFieldDefinition::CHECKBOX);
        $this->field->setType(CustomFieldDefinition::RADIO);
        $this->field->setType(CustomFieldDefinition::SELECT);
    }

    public function testJsonSerializeFilterEmpty()
    {
        $this->assertEmpty($this->field->jsonSerialize());
    }

    public function testJsonSerializeFilter()
    {
        $this->field->setName('test');
        $array = $this->field->jsonSerialize();
        $this->assertArrayHasKey('name', $array);
        $this->assertCount(1, $array);
    }

    public function testJsonSerializeFilterArray()
    {
        $this->field->setName('test')
            ->addOption(1);
        $array = $this->field->jsonSerialize();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('options', $array);
        $this->assertCount(2, $array);
    }

    public function testCreateValueFail()
    {
        // Test non saved custom field - without id
        try {
            $this->field->createValue();
        } catch (PropertyRequiredException $propertyRequiredException) {
            $this->assertEquals(
                'You must register the custom field - missing id',
                $propertyRequiredException->getMessage()
            );
        }
    }

    public function testCreateValue()
    {
        $importField = $this->field->setId(10)
            ->createValue('Test');

        $this->assertInstanceOf(CustomFieldValue::class, $importField);
        $this->assertEquals(10, $importField->id);
        $this->assertEquals('Test', $importField->value);
    }

    public function testCreateValueEmpty()
    {
        $importField = $this->field->setId(11)
            ->createValue();

        $this->assertInstanceOf(CustomFieldValue::class, $importField);
        $this->assertEquals(11, $importField->id);
        $this->assertNull($importField->value);
    }
}
