<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\CustomFieldValue as ImportCustomField;
use SmartEmailing\v3\Models\Holder\CustomFieldOptions;

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

    /**
     * Options allowed in sort parameter of query
     */
    public const SORT_FIELDS = self::SELECT_FIELDS;

    /**
     * Options allowed in select parameter of query
     */
    public const EXPAND_FIELDS = ['customfield_options'];

    protected ?int $id = null;

    protected ?string $name = null;

    protected ?string $type = null;

    private CustomFieldOptions $options;

    public function __construct(?string $name = null, ?string $type = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }

        if ($type !== null) {
            $this->setType($type);
        }
        $this->options = new CustomFieldOptions();
    }

    /**
     * Returns a list of supported types
     */
    public static function types(): array
    {
        return [self::TEXT, self::TEXT_AREA, self::DATE, self::CHECKBOX, self::RADIO, self::SELECT];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|numeric-string|null $id
     */
    public function setId($id): self
    {
        $this->id = $id !== null ? (int) $id : null;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        // Check if valid
        InvalidFormatException::checkInArray($type, static::types());
        $this->type = $type;
        return $this;
    }

    public function options(): CustomFieldOptions
    {
        return $this->options;
    }

    /**
     * Creates import custom field object from the CustomField
     *
     * @param string|null $value String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields.
     * Value size is limited to
     * 64KB. Required for simple custom-fields
     */
    public function createValue(?string $value = null): ImportCustomField
    {
        PropertyRequiredException::throwIf(
            'id',
            is_numeric($this->id),
            'You must register the custom field - missing id'
        );
        return new ImportCustomField($this->id, $value);
    }

    /**
     * Creates option object from the CustomField
     */
    public function createOption(?string $name = null): CustomFieldOption
    {
        PropertyRequiredException::throwIf(
            'id',
            is_numeric($this->id),
            'You must register the custom field - missing id'
        );
        return new CustomFieldOption($this->id, $name);
    }

    /**
     * Returns the array representation
     *
     * @return array{id: mixed, name: mixed, type: mixed}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }

    /**
     * @return static
     */
    public static function fromJSON(\stdClass $json): object
    {
        $item = parent::fromJSON($json);

        if (isset($json->customfield_options)) {
            foreach ($json->customfield_options as $value) {
                $item->options()
                    ->add(CustomFieldOption::fromJSON($value));
            }
        }

        return $item;
    }
}
