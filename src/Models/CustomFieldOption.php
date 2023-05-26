<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

class CustomFieldOption extends Model
{
    /**
     * Options allowed in select parameter of query
     */
    public const SELECT_FIELDS = ['id', 'customfield_id', 'name', 'type'];

    /**
     * Options allowed in sort parameter of query
     */
    public const SORT_FIELDS = self::SELECT_FIELDS;

    public ?int $id = null;

    public ?int $customFieldId = null;

    public ?string $name = null;

    public ?int $order = null;

    public ?array $options = [];

    public function __construct(?int $customFieldId = null, ?string $name = null, ?int $order = 1)
    {
        if ($customFieldId !== null) {
            $this->setCustomFieldId($customFieldId);
        }

        if ($name !== null) {
            $this->setName($name);
        }

        if ($order !== null) {
            $this->setOrder($order);
        }
    }

    /**
     * @param int|numeric-string|null $id
     */
    public function setId($id): self
    {
        $this->id = $id !== null ? (int) $id : null;
        return $this;
    }

    public function setCustomFieldId(?int $customFieldId): self
    {
        $this->customFieldId = $customFieldId;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Returns the array representation
     *
     * @return array{id: ?int, customfield_id: ?int, name: ?string, order: ?int}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->customFieldId,
            'customfield_id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
