<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Models\Purpose;

/**
 * @extends AbstractMapHolder<Purpose>
 */
class Purposes extends AbstractMapHolder
{
    /**
     * Inserts purposes into the items. Unique items only.
     *
     * @return $this
     */
    public function add(Purpose $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates Purpose entry and inserts it to the array
     *
     * @param string|null $valid_from   Date and time since processing purpose is valid in YYYY-MM-DD HH:MM:SS format.
     * If empty, current date and time will be used.
     * @param string|null $valid_to     Date and time of processing purpose validity end in YYYY-MM-DD HH:MM:SS format.
     * If empty, it will be calculated as valid_from + default duration of particular
     * purpose.
     */
    public function create(int $id, ?string $valid_from = null, ?string $valid_to = null): Purpose
    {
        $field = new Purpose($id, $valid_from, $valid_to);
        $this->add($field);
        return $field;
    }

    protected function entryKey(Model $entry): ?int
    {
        return $entry->id;
    }
}
