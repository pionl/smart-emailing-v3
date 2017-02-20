<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class CustomFieldTest extends BaseTestCase
{
    /**
     * @var CustomField
     */
    protected $field;

    protected function setUp()
    {
        parent::setUp();
        $this->field = new CustomField();
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
        $field = new CustomField('Test', CustomField::CHECKBOX);

        $this->assertEquals('Test', $field->name);
        $this->assertEquals(CustomField::CHECKBOX, $field->type);
        $this->assertTrue(is_array($field->options), 'Options must be an array');
        $this->assertEmpty($field->options);
    }

    public function testConstructInvalidType()
    {
        try {
            new CustomField('Test', 'test');
            $this->fail('The construct should use setType method that checks the correct type');
        } catch (InvalidFormatException $exception) {
            // Check if all the types are passed
            $this->assertEquals(
                "Value 'test' not allowed: text, textarea, date, checkbox, radio, select",
                $exception->getMessage()
            );
        }
    }

    public function testSetType()
    {
        // Test if set
        $this->assertEquals(CustomField::CHECKBOX, $this->field->setType(CustomField::CHECKBOX)->type);
        $this->field->setType(CustomField::TEXT_AREA);
        $this->field->setType(CustomField::DATE);
        $this->field->setType(CustomField::CHECKBOX);
        $this->field->setType(CustomField::RADIO);
        $this->field->setType(CustomField::SELECT);
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
        $this->field->setName('test')->addOption(1);
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
        } catch (PropertyRequiredException $exception) {
            $this->assertEquals('You must register the custom field - missing id', $exception->getMessage());
        }
    }

    public function testCreateValue()
    {
        $importField = $this->field->setId(10)->createValue('Test');

        $this->assertInstanceOf(\SmartEmailing\v3\Request\Import\CustomField::class, $importField);
        $this->assertEquals(10, $importField->id);
        $this->assertEquals('Test', $importField->value);
    }

    public function testCreateValueEmpty()
    {
        $importField = $this->field->setId(11)->createValue();

        $this->assertInstanceOf(\SmartEmailing\v3\Request\Import\CustomField::class, $importField);
        $this->assertEquals(11, $importField->id);
        $this->assertNull($importField->value);
    }
}