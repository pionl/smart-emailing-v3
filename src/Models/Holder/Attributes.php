<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\Attribute;

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
    public function create($name, $value): Attribute
    {
        $list = new Attribute($name, $value);
        $this->add($list);
        return $list;
    }

    /**
     * Adds an entry model into items list. Only unique items are added (represented by the id property)
     *
     * @return boolean if the entry was added (first time added)
     */
    protected function insertEntry($entry): bool
    {
        // Allow only unique values
        if (isset($this->idMap[$entry->name])) {
            return false;
        }

        $this->items[] = $entry;
        $this->idMap[$entry->name] = $entry;

        return true;
    }
}
