<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Attribute field wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class Attribute extends Model
{
    protected ?string $name = null;

    /**
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
     */
    protected ?string $value = null;

    public function __construct(?string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array{name: string|null, value: string|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
