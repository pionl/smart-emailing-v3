<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\CustomFieldOption;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractMapHolder<CustomFieldOption>
 */
class CustomFieldOptions extends AbstractMapHolder
{
    /**
     * Inserts custom filed into the items. Unique items only.
     *
     * @return $this
     */
    public function add(CustomFieldOption $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates CustomFiledOption entry and inserts it to the array composite customfields
     */
    public function create(int $id, ?string $name = null, int $order = 1): CustomFieldOption
    {
        $field = new CustomFieldOption($id, $name, $order);
        $this->add($field);
        return $field;
    }

    protected function entryKey(Model $entry): ?int
    {
        return $entry->id;
    }
}
