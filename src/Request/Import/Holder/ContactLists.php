<?php
namespace SmartEmailing\v3\Request\Import\Holder;

use SmartEmailing\v3\Models\AbstractHolder;
use SmartEmailing\v3\Request\Import\ContactList;

class ContactLists extends AbstractHolder
{
    protected $idMap = [];
    /**
     * @param ContactList $list
     *
     * @return $this
     */
    public function add(ContactList $list)
    {
        // Allow only unique values
        if (isset($this->idMap[$list->id])) {
            return $this;
        }

        $this->items[] = $list;
        $this->idMap[$list->id] = $list;
        return $this;
    }

    /**
     * Creates ContactList entry and inserts it to the array
     *
     * @param int         $id
     * @param string|null $status Contact's status in Contactlist. Allowed values: "confirmed", "unsubscribed",
     *                            "removed"
     *
     * @return ContactList
     */
    public function create($id, $status = null)
    {
        $list = new ContactList($id, $status);
        $this->add($list);
        return $list;
    }
}