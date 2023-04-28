<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;

/**
 * Attribute field wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class Attribute extends Model
{
    /**
     * @var string|null
     */
    public $name = null;

    /**
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
     *
     * @var string|null
     */
    public $value = null;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove null/empty values - not needed
        return $this->toArray();
    }
}
