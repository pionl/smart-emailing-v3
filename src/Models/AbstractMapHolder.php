<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Class AbstractMapHolder
 *
 * Supports map of ids to ensure only unique values
 */
abstract class AbstractMapHolder extends AbstractHolder
{
    protected $idMap = [];

    /**
     * Adds an entry model into items list. Only unique items are added (represented by the id property)
     *
     * @return boolean if the entry was added (first time added)
     */
    protected function insertEntry($entry): bool
    {
        // Allow only unique values
        if (isset($this->idMap[$entry->id])) {
            return false;
        }

        $this->items[] = $entry;
        $this->idMap[$entry->id] = $entry;

        return true;
    }
}
