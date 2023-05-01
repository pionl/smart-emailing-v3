<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\CustomFieldValue;

class CustomFieldValues extends AbstractMapHolder
{
    /**
     * Inserts custom filed into the items. Unique items only.
     *
     * @return $this
     */
    public function add(CustomFieldValue $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates ContactList entry and inserts it to the array
     *
     * @param int         $id
     * @param string|null $value   String value for simple customfields, and YYYY-MM-DD HH:MM:SS for date customfields.
     * Value size is limited to  64KB. Required for simple customfields
     * @param array       $options Array of Customfields options IDs matching with selected Customfield. Required for
     * composite customfields
     */
    public function create($id, $value = null, $options = []): CustomFieldValue
    {
        $field = new CustomFieldValue($id, $value);
        $field->setOptions($options);
        $this->add($field);
        return $field;
    }
}
