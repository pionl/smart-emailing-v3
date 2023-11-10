<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * @template TEntry of Model
 */
abstract class AbstractHolder implements \JsonSerializable
{
    /**
     * A list of items
     *
     * @var TEntry[]
     */
    protected array $items = [];

    /**
     * @param int|string $index
     * @return TEntry
     */
    public function get($index): Model
    {
        if (isset($this->items[$index]) === false) {
            throw new \InvalidArgumentException(sprintf('Index %s does not exist', $index));
        }
        return $this->items[$index];
    }

    /**
     * @param int|string $index
     */
    public function has($index): bool
    {
        return isset($this->items[$index]);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return TEntry[]
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return TEntry[]
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
