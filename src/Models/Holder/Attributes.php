<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\Attribute;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractMapHolder<Attribute>
 */
class Attributes extends AbstractMapHolder
{
    /**
     * Inserts attribute into the attributes.
     */
    public function add(Attribute $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates Attribute entry and inserts it to the array
     */
    public function create(?string $name, ?string $value): Attribute
    {
        $list = new Attribute($name, $value);
        $this->add($list);
        return $list;
    }

    /**
     * @param Attribute $entry
     */
    protected function insertEntry(Model $entry): bool
    {
        // Allow only unique values
        if (isset($this->idMap[$entry->name])) {
            return false;
        }

        $this->items[] = $entry;
        $this->idMap[$entry->name] = $entry;

        return true;
    }

    protected function entryKey(Model $entry): ?string
    {
        return $entry->name;
    }
}
