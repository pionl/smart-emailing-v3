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
    protected CustomFieldDefinition $field;

    protected function setUp(): void
    {
        parent::setUp();
        $this->field = new CustomFieldDefinition();
    }

    public function testEmptyConstruct(): void
    {
        $this->assertNull($this->field->getName());
        $this->assertNull($this->field->getType());
        $this->assertNull($this->field->getId());
    }

    public function testConstruct(): void
    {
        $field = new CustomFieldDefinition('Test', CustomFieldDefinition::CHECKBOX);

        $this->assertEquals('Test', $field->getName());
        $this->assertEquals(CustomFieldDefinition::CHECKBOX, $field->getType());
    }

    public function testConstructInvalidType(): void
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

    public function testSetType(): void
    {
        // Test if set
        $this->assertEquals(
            CustomFieldDefinition::CHECKBOX,
            $this->field->setType(CustomFieldDefinition::CHECKBOX)->getType()
        );
        $this->field->setType(CustomFieldDefinition::TEXT_AREA);
        $this->field->setType(CustomFieldDefinition::DATE);
        $this->field->setType(CustomFieldDefinition::CHECKBOX);
        $this->field->setType(CustomFieldDefinition::RADIO);
        $this->field->setType(CustomFieldDefinition::SELECT);
    }

    public function testJsonSerializeFilterEmpty(): void
    {
        $this->assertEmpty($this->field->jsonSerialize());
    }

    public function testJsonSerializeFilter(): void
    {
        $this->field->setName('test');
        $array = $this->field->jsonSerialize();
        $this->assertArrayHasKey('name', $array);
        $this->assertCount(1, $array);
    }

    public function testCreateValueFail(): void
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

    public function testCreateValue(): void
    {
        $importField = $this->field->setId(10)
            ->createValue('Test');

        $this->assertInstanceOf(CustomFieldValue::class, $importField);
        $this->assertEquals(10, $importField->getId());
        $this->assertEquals('Test', $importField->getValue());
    }

    public function testCreateValueEmpty(): void
    {
        $importField = $this->field->setId(11)
            ->createValue();

        $this->assertInstanceOf(CustomFieldValue::class, $importField);
        $this->assertEquals(11, $importField->getId());
        $this->assertNull($importField->getValue());
    }
}
