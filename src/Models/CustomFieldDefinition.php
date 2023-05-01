<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\CustomFieldValue as ImportCustomField;

class CustomFieldDefinition extends Model
{
    public const TEXT = 'text';
    public const TEXT_AREA = 'textarea';
    public const DATE = 'date';
    public const CHECKBOX = 'checkbox';
    public const RADIO = 'radio';
    public const SELECT = 'select';

    /**
     * Options allowed in select parameter of query
     */
    public const SELECT_FIELDS = ['id', 'name', 'type'];

    public $id = null;

    public $name = null;

    public $type = null;

    public $options = [];

    /**
     * CustomField constructor.
     *
     * @param string|null $name
     * @param string|null $type
     */
    public function __construct($name = null, $type = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }

        if ($type !== null) {
            $this->setType($type);
        }
    }

    /**
     * Returns a list of supported types
     *
     * @return array
     */
    public static function types()
    {
        return [self::TEXT, self::TEXT_AREA, self::DATE, self::CHECKBOX, self::RADIO, self::SELECT];
    }

    /**
     * @param int|numeric-string|null $id
     *
     * @return CustomFieldDefinition
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Adds an option id
     *
     * @param int $id
     *
     * @return $this
     */
    public function addOption($id)
    {
        $this->options[] = $id;
        return $this;
    }

    /**
     * @param mixed $name
     *
     * @return CustomFieldDefinition
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return CustomFieldDefinition
     */
    public function setType($type)
    {
        // Check if valid
        InvalidFormatException::checkInArray($type, static::types());
        $this->type = $type;
        return $this;
    }

    /**
     * Creates import custom field object from the CustomField
     *
     * @param string|null $value String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields.
     * Value size is limited to
     * 64KB. Required for simple custom-fields
     *
     * @return ImportCustomField
     */
    public function createValue($value = null)
    {
        PropertyRequiredException::throwIf(
            'id',
            is_numeric($this->id),
            'You must register the custom field - missing id'
        );
        return new ImportCustomField($this->id, $value);
    }

    /**
     * Returns the array representation
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'options' => $this->options,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
