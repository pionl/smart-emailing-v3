<?php
namespace SmartEmailing\v3\Request\Import\Holder;

use SmartEmailing\v3\Request\Import\CustomField;

class CustomFields extends AbstractHolder
{
    /**
     * @param CustomField $field
     *
     * @return $this
     */
    public function add(CustomField $field)
    {
        $this->items[] = $field;
        return $this;
    }

    /**
     * Creates ContactList entry and inserts it to the array
     *
     * @param int         $id
     * @param string|null $value   String value for simple customfields, and YYYY-MM-DD HH:MM:SS for date customfields.
     *                             Value size is limited to
     *                             64KB. Required for simple customfields
     * @param array       $options Array of Customfields options IDs matching with selected Customfield. Required for
     *                             composite customfields
     *
     * @return CustomField
     */
    public function create($id, $value = null, $options = [])
    {
        $field = new CustomField($id, $value);
        $field->setOptions($options);
        $this->add($field);
        return $field;
    }
}