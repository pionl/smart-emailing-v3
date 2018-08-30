<?php
namespace SmartEmailing\v3\Request\Import\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Request\Import\Purpose;

class Purposes extends AbstractMapHolder
{
    /**
     * Inserts purposes into the items. Unique items only.
     *
     * @param Purpose $list
     *
     * @return $this
     */
    public function add(Purpose $list)
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates Purpose entry and inserts it to the array
     *
     * @param int         $id
     * @param string|null $valid_from   Date and time since processing purpose is valid in YYYY-MM-DD HH:MM:SS format.
     *                                  If empty, current date and time will be used.
     * @param string|null $valid_to     Date and time of processing purpose validity end in YYYY-MM-DD HH:MM:SS format.
     *                                  If empty, it will be calculated as valid_from + default duration of particular
     *                                  purpose.
     *
     * @return Purpose
     */
    public function create($id, $valid_from = null, $valid_to = null)
    {
        $field = new Purpose($id, $valid_from, $valid_to);
        $this->add($field);
        return $field;
    }
}