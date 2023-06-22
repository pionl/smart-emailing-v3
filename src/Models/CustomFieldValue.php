<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Contact field wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class CustomFieldValue extends Model
{
    protected ?int $id = null;

    /**
     * Array of Customfields options IDs matching with selected Custom-field. Required for composite custom-fields
     */
    protected array $options = [];

    /**
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
     */
    protected ?string $value = null;

    /**
     * @param int|numeric-string|null    $id
     * @param string|null $value String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields.
     * Value size is limited to
     * 64KB. Required for simple custom-fields
     */
    public function __construct($id = null, ?string $value = null)
    {
        $this->setId($id);

        if ($value !== null) {
            $this->setValue($value);
        }
    }

    /**
     * Adds a CustomField id for composite custom-fields
     *
     * @return $this
     */
    public function addOption(int $customFiledId)
    {
        $this->options[] = (int) $customFiledId;
        return $this;
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

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Array of Customfields options IDs matching with selected Customfield. Required for composite customfields
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array{id: int|null, options: mixed[], value: string|null}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'options' => $this->options,
            'value' => $this->value,
        ];
    }

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
        if (isset($json->customfield_id)) {
            $item->id = (int) $json->customfield_id;
        }
        if (isset($json->customfield_options_id)) {
            $item->addOption($json->customfield_options_id);
        }
        return $item;
    }
}
