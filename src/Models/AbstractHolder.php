<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

abstract class AbstractHolder implements \JsonSerializable
{
    /**
     * A list of items
     *
     * @var array
     */
    protected $items = [];

    /**
     * @return mixed
     */
    public function get($index)
    {
        return $this->items[$index];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->items === [];
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
