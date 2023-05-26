<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Supports map of ids to ensure only unique values
 *
 * @template TEntry of Model
 * @extends AbstractHolder<TEntry>
 */
abstract class AbstractMapHolder extends AbstractHolder
{
    /**
     * @var array TEntry[]
     */
    protected array $idMap = [];

    /**
     * @param int|string $id
     * @return TEntry
     */
    public function getById($id): Model
    {
        if (isset($this->idMap[$id]) === false) {
            throw new \InvalidArgumentException(sprintf('Id %s does not exist', $id));
        }
        return $this->idMap[$id];
    }

    /**
     * @param int|string $id
     */
    public function hasId($id): bool
    {
        return isset($this->idMap[$id]);
    }

    /**
     * Adds an entry model into items list. Only unique items are added (represented by the id property)
     *
     * @param TEntry $entry
     * @return boolean if the entry was added (first time added)
     */
    protected function insertEntry(Model $entry): bool
    {
        // Allow only unique values
        if (isset($this->idMap[$this->entryKey($entry)])) {
            return false;
        }

        $this->items[] = $entry;
        $this->idMap[$this->entryKey($entry)] = $entry;

        return true;
    }

    /**
     * @param TEntry $entry
     * @return int|string|null
     */
    abstract protected function entryKey(Model $entry);
}
